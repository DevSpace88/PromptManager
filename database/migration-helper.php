<?php

/**
 * Custom migration script with enhanced retry logic for database connections
 * This script is designed to be run during deployment to handle database connection issues
 */

// Bootstrap the Laravel application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

// Configuration
$maxAttempts = 15;
$initialWaitTime = 10; // seconds
$maxWaitTime = 60; // seconds
$totalTimeout = 600; // 10 minutes total timeout

echo "Starting migration with enhanced retry logic for Railway...\n";
echo "Max attempts: $maxAttempts, Initial wait: $initialWaitTime sec, Max wait: $maxWaitTime sec, Total timeout: $totalTimeout sec\n";

// Check if DATABASE_URL is available
if (env('DATABASE_URL')) {
    echo "DATABASE_URL detected: " . substr(env('DATABASE_URL'), 0, 20) . "...\n";
} else {
    echo "WARNING: DATABASE_URL not found. Using default database configuration.\n";
}

$startTime = time();
$attempt = 0;
$waitTime = $initialWaitTime;
$success = false;

while ($attempt < $maxAttempts && (time() - $startTime) < $totalTimeout) {
    $attempt++;
    echo "\n=== Attempt $attempt of $maxAttempts (Elapsed time: " . (time() - $startTime) . " seconds) ===\n";

    try {
        // Clear any cached configurations
        echo "Clearing configuration cache...\n";
        Artisan::call('config:clear');
        
        // Test database connection with timeout
        echo "Testing database connection... ";
        
        // Set connection timeout
        $connection = DB::connection();
        $pdo = $connection->getPdo();
        
        // Test with a simple query
        $pdo->query('SELECT 1');
        echo "SUCCESS\n";

        // Check if migrations table exists
        echo "Checking migrations table... ";
        $tableExists = $connection->getSchemaBuilder()->hasTable('migrations');
        echo ($tableExists ? "EXISTS" : "DOES NOT EXIST") . "\n";

        // Run the migration with force flag
        echo "Running migrations...\n";
        $exitCode = Artisan::call('migrate', [
            '--force' => true,
            '--no-interaction' => true
        ]);

        if ($exitCode === 0) {
            echo "Migration completed successfully!\n";
            $success = true;
            break;
        } else {
            echo "Migration failed with exit code: $exitCode\n";
            throw new Exception("Migration command failed with exit code: $exitCode");
        }
    } catch (\PDOException $e) {
        echo "PDO ERROR: " . $e->getMessage() . "\n";
        Log::error("Migration attempt $attempt PDO error: " . $e->getMessage());
        
        // Check if it's a connection refused error
        if (strpos($e->getMessage(), 'Connection refused') !== false || 
            strpos($e->getMessage(), 'Connection timed out') !== false ||
            strpos($e->getMessage(), 'No such file or directory') !== false) {
            echo "Database connection issue detected. This is expected during Railway deployment.\n";
        }
        
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        Log::error("Migration attempt $attempt failed: " . $e->getMessage());
    }

    // If we've reached the maximum attempts, throw the exception
    if ($attempt >= $maxAttempts || (time() - $startTime) >= $totalTimeout) {
        echo "Maximum attempts reached or timeout exceeded. Migration failed.\n";
        echo "Last error: " . (isset($e) ? $e->getMessage() : 'Unknown error') . "\n";
        exit(1);
    }

    echo "Waiting $waitTime seconds before retrying...\n";
    sleep($waitTime);

    // Increase wait time for next attempt (exponential backoff with cap)
    $waitTime = min($waitTime * 1.5, $maxWaitTime);
}

if (!$success) {
    echo "Migration failed after $attempt attempts and " . (time() - $startTime) . " seconds.\n";
    echo "This might be due to:\n";
    echo "1. Database service not ready on Railway\n";
    echo "2. Incorrect DATABASE_URL configuration\n";
    echo "3. Network connectivity issues\n";
    exit(1);
}

echo "\nMigration process completed successfully after $attempt attempts and " . (time() - $startTime) . " seconds.\n";
exit(0);
