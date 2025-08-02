<?php

/**
 * Simple database connection test script for Railway deployment
 * Run this to test if the database connection is working
 */

// Bootstrap the Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

echo "=== Railway Database Connection Test ===\n\n";

// Check environment variables
echo "Environment Variables:\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION', 'not set') . "\n";
echo "DB_HOST: " . env('DB_HOST', 'not set') . "\n";
echo "DB_PORT: " . env('DB_PORT', 'not set') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE', 'not set') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME', 'not set') . "\n";
echo "DATABASE_URL: " . (env('DATABASE_URL') ? substr(env('DATABASE_URL'), 0, 50) . '...' : 'not set') . "\n\n";

// Check database configuration
echo "Database Configuration:\n";
$defaultConnection = Config::get('database.default');
echo "Default connection: $defaultConnection\n";

$connectionConfig = Config::get("database.connections.$defaultConnection");
if ($connectionConfig) {
    echo "Host: " . ($connectionConfig['host'] ?? 'not set') . "\n";
    echo "Port: " . ($connectionConfig['port'] ?? 'not set') . "\n";
    echo "Database: " . ($connectionConfig['database'] ?? 'not set') . "\n";
    echo "Username: " . ($connectionConfig['username'] ?? 'not set') . "\n";
    echo "Driver: " . ($connectionConfig['driver'] ?? 'not set') . "\n";
} else {
    echo "No configuration found for connection: $defaultConnection\n";
}

echo "\n";

// Test connection
echo "Testing database connection...\n";
try {
    $connection = DB::connection();
    $pdo = $connection->getPdo();
    
    echo "✓ PDO connection established successfully\n";
    
    // Test with a simple query
    $result = $pdo->query('SELECT 1 as test')->fetch();
    echo "✓ Test query executed successfully: " . $result['test'] . "\n";
    
    // Check if migrations table exists
    $tableExists = $connection->getSchemaBuilder()->hasTable('migrations');
    echo "✓ Migrations table: " . ($tableExists ? 'EXISTS' : 'DOES NOT EXIST') . "\n";
    
    echo "\n🎉 Database connection test PASSED!\n";
    exit(0);
    
} catch (\PDOException $e) {
    echo "✗ PDO Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    exit(1);
} catch (\Exception $e) {
    echo "✗ General Error: " . $e->getMessage() . "\n";
    exit(1);
} 