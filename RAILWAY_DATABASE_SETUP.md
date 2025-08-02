# Railway Database Connection Setup

## Issue
The application was failing to connect to the MySQL database during deployment on Railway. The error message indicated a connection refusal:

```
SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)
```

## Solution

### 1. Updated AppServiceProvider.php

Added code to parse the `DATABASE_URL` environment variable provided by Railway and configure the MySQL connection accordingly:

```php
public function register(): void
{
    // Parse DATABASE_URL for Railway MySQL connection if available
    if ($databaseUrl = env('DATABASE_URL')) {
        $url = parse_url($databaseUrl);
        
        if (isset($url['scheme']) && $url['scheme'] === 'mysql') {
            config([
                'database.default' => 'mysql',
                'database.connections.mysql.host' => $url['host'] ?? '127.0.0.1',
                'database.connections.mysql.port' => $url['port'] ?? '3306',
                'database.connections.mysql.database' => ltrim($url['path'] ?? '', '/'),
                'database.connections.mysql.username' => $url['user'] ?? '',
                'database.connections.mysql.password' => $url['pass'] ?? '',
            ]);
        }
    }
}
```

### 2. Updated nixpack.toml

Added a `php artisan config:clear` command before running migrations to ensure the updated configuration is used:

```toml
[phases.build]
cmds = [
  "composer install --no-dev --optimize-autoloader",
  "npm ci && npm run build",
  "php artisan key:generate --force",
  "php artisan config:clear",
  "php artisan migrate --force",
  "php artisan config:cache",
  "php artisan route:cache",
  "php artisan view:cache",
  "chmod -R 775 storage bootstrap/cache"
]
```

## How It Works

1. Railway provides a `DATABASE_URL` environment variable in the format: `mysql://username:password@hostname:port/database_name`
2. The AppServiceProvider parses this URL and configures the MySQL connection accordingly
3. The `config:clear` command ensures that any cached configuration is cleared before running migrations
4. The application can now connect to the MySQL database provided by Railway

## Local Development

Local development still uses SQLite as configured in the `.env` file. The MySQL configuration is only used when the `DATABASE_URL` environment variable is present, which is typically only in the Railway environment.
