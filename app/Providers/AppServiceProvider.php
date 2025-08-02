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
                // Configure database connection for Railway
                config([
                    'database.default' => 'mysql',
                    'database.connections.mysql.host' => $url['host'] ?? '127.0.0.1',
                    'database.connections.mysql.port' => $url['port'] ?? '3306',
                    'database.connections.mysql.database' => ltrim($url['path'] ?? '', '/'),
                    'database.connections.mysql.username' => $url['user'] ?? '',
                    'database.connections.mysql.password' => $url['pass'] ?? '',
                    'database.connections.mysql.charset' => 'utf8mb4',
                    'database.connections.mysql.collation' => 'utf8mb4_unicode_ci',
                    'database.connections.mysql.prefix' => '',
                    'database.connections.mysql.strict' => true,
                    'database.connections.mysql.engine' => null,
                ]);
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
