<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Parse DATABASE_URL for Railway MySQL connection if available
        if ($databaseUrl = env('DATABASE_URL')) {
            $url = parse_url($databaseUrl);

            if (isset($url['scheme']) && $url['scheme'] === 'mysql') {
                // Get existing options from config
                $existingOptions = config('database.connections.mysql.options', []);

                // Merge with our enhanced options for Railway
                $options = array_merge($existingOptions, [
                    \PDO::ATTR_TIMEOUT => 60,
                    \PDO::ATTR_PERSISTENT => true,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    // Add initialization command for MySQL
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET wait_timeout=300',
                ]);

                // Configure database connection with enhanced settings
                config([
                    'database.default' => 'mysql',
                    'database.connections.mysql.host' => $url['host'] ?? '127.0.0.1',
                    'database.connections.mysql.port' => $url['port'] ?? '3306',
                    'database.connections.mysql.database' => ltrim($url['path'] ?? '', '/'),
                    'database.connections.mysql.username' => $url['user'] ?? '',
                    'database.connections.mysql.password' => $url['pass'] ?? '',
                    'database.connections.mysql.options' => $options,
                ]);

                // Register a database connection event listener for connection issues
                \Illuminate\Support\Facades\Event::listen('Illuminate\Database\Events\QueryExecuted', function ($event) {
                    // This event listener will be triggered for each query
                    // We don't need to do anything here, but it ensures the database connection is established
                });

                // Add a custom exception handler for database connection issues
                $this->app->singleton('db.connection.factory', function ($app) {
                    return new class($app) extends \Illuminate\Database\Connectors\ConnectionFactory {
                        protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
                        {
                            try {
                                return parent::createConnection($driver, $connection, $database, $prefix, $config);
                            } catch (\Exception $e) {
                                if (strpos($e->getMessage(), 'Connection refused') !== false) {
                                    // Log the error
                                    \Illuminate\Support\Facades\Log::error("Database connection refused. Retrying in 3 seconds...");

                                    // Wait before retrying
                                    sleep(3);

                                    // Try again
                                    return parent::createConnection($driver, $connection, $database, $prefix, $config);
                                }

                                throw $e;
                            }
                        }
                    };
                });
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // HTTPS für Production erzwingen
        if ($this->app->environment('production') || config('app.force_https')) {
            URL::forceScheme('https');

            // Proxy-Headers für Railway vertrauen
            request()->server->set('HTTPS', 'on');
            request()->server->set('SERVER_PORT', 443);
        }
    }
}
