#!/bin/bash
# .railway/start.sh (Finale Version)

echo "Waiting for database to be ready..."
timeout 60 bash -c 'until (</dev/tcp/$DB_HOST/$DB_PORT) &>/dev/null; do sleep 1; done'
if [ $? -ne 0 ]; then
  echo "Database did not become available in time. Exiting."
  exit 1
fi
echo "Database is ready."

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- START DER SERVER-KONFIGURATION ---

# 1. Nginx konfigurieren
echo "Configuring Nginx..."
sudo mkdir -p /etc/nginx/
sudo cp ./.railway/nginx.conf /etc/nginx/nginx.conf

# 2. PHP-FPM konfigurieren
echo "Configuring PHP-FPM..."
# Wir geben PHP-FPM den vollen Pfad zu unserer Konfigurationsdatei.
# Das -y Flag sagt "benutze diese Konfigurationsdatei".
PHP_FPM_CONF_PATH="/app/.railway/php-fpm.conf"

# 3. Das Verzeichnis für den PHP-Socket erstellen und Berechtigungen setzen
sudo mkdir -p /run/php
sudo chown www-data:www-data /run/php

# 4. PHP-FPM im Hintergrund starten (mit korrekter Konfiguration)
echo "Starting PHP-FPM..."
sudo $(which php-fpm) -y ${PHP_FPM_CONF_PATH} -D

# 5. Nginx im Vordergrund starten (mit korrektem Pfad)
echo "Starting Nginx..."
sudo $(which nginx) -g "daemon off;"
