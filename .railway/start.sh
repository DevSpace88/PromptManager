#!/bin/bash
# .railway/start.sh

# Warte darauf, dass der Datenbank-Host auf dem Port erreichbar ist.
# Wir verwenden eine eingebaute Bash-Funktion anstelle von `nc`.
echo "Waiting for database to be ready..."
timeout 30 bash -c 'until (</dev/tcp/$DB_HOST/$DB_PORT) &>/dev/null; do sleep 1; done'
if [ $? -ne 0 ]; then
  echo "Database did not become available in time. Exiting."
  exit 1
fi
echo "Database is ready."

# Führe Migrationen aus.
echo "Running migrations..."
php artisan migrate --force

# Caching für bessere Performance in der Produktion
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Konfigurationsdatei für Nginx kopieren
echo "Configuring Nginx..."
sudo mkdir -p /etc/nginx/
sudo cp ./.railway/nginx.conf /etc/nginx/nginx.conf

# PHP-FPM im Hintergrund starten
echo "Starting PHP-FPM..."
sudo $(which php-fpm) -D

# Nginx im Vordergrund starten (damit der Container nicht beendet wird)
echo "Starting Nginx..."
sudo nginx -g "daemon off;"
