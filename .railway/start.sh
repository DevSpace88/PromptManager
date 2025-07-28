#!/bin/bash
# .railway/start.sh (Railway-optimiert)

echo "Starting Railway deployment..."

# Setze Standard-Port falls nicht gesetzt
export PORT=${PORT:-8080}

# Erstelle notwendige Verzeichnisse ohne sudo
mkdir -p /tmp/nginx
mkdir -p /tmp/php
mkdir -p /tmp/logs

# Prüfe auf Datenbank-Verfügbarkeit nur wenn DB konfiguriert
if [ ! -z "$DATABASE_URL" ] || [ ! -z "$DB_HOST" ]; then
    echo "Waiting for database to be ready..."
    
    # Extrahiere DB-Details aus DATABASE_URL falls verfügbar
    if [ ! -z "$DATABASE_URL" ]; then
        # PostgreSQL URL Format: postgresql://user:password@host:port/database
        DB_HOST=$(echo $DATABASE_URL | sed -n 's/.*@\([^:]*\):.*/\1/p')
        DB_PORT=$(echo $DATABASE_URL | sed -n 's/.*:\([0-9]*\)\/.*/\1/p')
    fi
    
    # Fallback-Port für PostgreSQL
    DB_PORT=${DB_PORT:-5432}
    
    if [ ! -z "$DB_HOST" ]; then
        timeout 30 bash -c "until nc -z $DB_HOST $DB_PORT; do echo 'Waiting for DB...'; sleep 2; done" || echo "Database check timed out, continuing..."
    fi
fi

echo "Setting up Laravel..."

# Laravel-Optimierungen
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Nur Migrationen ausführen wenn Datenbank verfügbar
if [ ! -z "$DATABASE_URL" ] || [ ! -z "$DB_HOST" ]; then
    echo "Running migrations..."
    php artisan migrate --force || echo "Migration failed, continuing..."
fi

# Cache-Befehle
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- SERVER-KONFIGURATION ---

echo "Configuring services..."

# PHP-FPM Konfiguration anpassen für Railway
cat > /tmp/php-fpm.conf << 'EOF'
[global]
pid = /tmp/php/php-fpm.pid
error_log = /tmp/logs/php-fpm.log

[www]
user = nobody
group = nobody
listen = /tmp/php/php8.2-fpm.sock
listen.owner = nobody
listen.group = nobody
listen.mode = 0660

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 5

catch_workers_output = yes
EOF

# Nginx Konfiguration anpassen für Railway
cat > /tmp/nginx.conf << EOF
worker_processes auto;
pid /tmp/nginx/nginx.pid;
error_log /tmp/logs/nginx_error.log;

events {
    worker_connections 1024;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    
    access_log /tmp/logs/nginx_access.log;
    
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    
    client_body_temp_path /tmp/nginx/client_body;
    proxy_temp_path /tmp/nginx/proxy;
    fastcgi_temp_path /tmp/nginx/fastcgi;
    
    server {
        listen $PORT;
        server_name _;
        root /app/public;
        index index.php index.html;
        
        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }
        
        location = /favicon.ico { 
            access_log off; 
            log_not_found off; 
        }
        
        location = /robots.txt { 
            access_log off; 
            log_not_found off; 
        }
        
        location ~ \.php\$ {
            fastcgi_pass unix:/tmp/php/php8.2-fpm.sock;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
            include fastcgi_params;
            fastcgi_read_timeout 300;
        }
        
        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
EOF

# Starte PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -y /tmp/php-fpm.conf -D

# Warte kurz, damit PHP-FPM startet
sleep 2

# Starte Nginx im Vordergrund
echo "Starting Nginx on port $PORT..."
nginx -c /tmp/nginx.conf -g "daemon off;"
