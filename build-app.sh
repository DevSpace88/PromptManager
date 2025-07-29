#!/bin/bash
# Exit the script if any command fails
set -e

# Build assets using NPM
npm run build

# Clear cache
php artisan optimize:clear

# Cache the various components
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache