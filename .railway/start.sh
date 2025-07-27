#!/bin/bash
# .railway/start.sh

# Warten, bis die Datenbank erreichbar ist (optional, aber gute Praxis)
# Führe Migrationen aus. Das --force Flag ist wichtig in der Produktion.
echo "Running migrations..."
php artisan migrate --force

# Caching für bessere Performance in der Produktion
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Konfigurationsdatei für Nginx kopieren
echo "Configuring Nginx..."
cp ./.railway/nginx.conf /etc/nginx/nginx.conf

# PHP-FPM im Hintergrund starten
echo "Starting PHP-FPM..."
/usr/sbin/php-fpm8.2 -D

# Nginx im Vordergrund starten (damit der Container nicht beendet wird)
echo "Starting Nginx..."
nginx -g "daemon off;"
