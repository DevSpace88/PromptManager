#!/bin/bash
# .railway/start.sh (Railway-optimiert)

echo "Starting Railway deployment..."

# Setze Standard-Port falls nicht gesetzt
export PORT=${PORT:-8080}

# Erstelle notwendige Verzeichnisse ohne sudo
mkdir -p /tmp/nginx
mkdir -p /tmp/php
mkdir -p /tmp/logs

echo "Setting up Laravel..."

# Laravel-Optimierungen
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Versuche Datenbank-Verbindung mit Laravel selbst zu testen
if [ ! -z "$DATABASE_URL" ] || [ ! -z "$DB_HOST" ]; then
    echo "Testing database connection..."
    
    # Verwende Laravel's eigenen Database Check
    if php artisan migrate:status &>/dev/null; then
        echo "Database connection successful. Running migrations..."
        php artisan migrate --force
    else
        echo "Database not yet available or not configured. Skipping migrations..."
        echo "App will try to connect when needed."
    fi
else
    echo "No database configured. Skipping database operations..."
fi

# Cache-Befehle
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- SERVER-KONFIGURATION ---

echo "Configuring services..."

# Finde den aktuellen User
CURRENT_USER=$(whoami)
echo "Running as user: $CURRENT_USER"

# PHP-FPM Konfiguration - erlaubt root in Container-Umgebung
cat > /tmp/php-fpm.conf << 'EOF'
[global]
pid = /tmp/php/php-fpm.pid
error_log = /tmp/logs/php-fpm.log
daemonize = no

[www]
user = root
group = root
listen = /tmp/php/php8.2-fpm.sock
listen.owner = root
listen.group = root
listen.mode = 0660

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 5

catch_workers_output = yes
EOF

# Erstelle eine minimalistische mime.types Datei
cat > /tmp/mime.types << 'EOF'
types {
    text/html                             html htm shtml;
    text/css                              css;
    text/xml                              xml;
    image/gif                             gif;
    image/jpeg                            jpeg jpg;
    image/png                             png;
    image/svg+xml                         svg svgz;
    image/webp                            webp;
    application/javascript                js;
    application/json                      json;
    application/pdf                       pdf;
    application/zip                       zip;
    font/woff                             woff;
    font/woff2                            woff2;
}
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
    include /tmp/mime.types;
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

# Erstelle notwendige Temp-Verzeichnisse für Nginx
mkdir -p /tmp/nginx/client_body
mkdir -p /tmp/nginx/proxy  
mkdir -p /tmp/nginx/fastcgi

# Starte PHP-FPM im Hintergrund mit --allow-to-run-as-root Flag
echo "Starting PHP-FPM as root (container environment)..."
php-fpm -y /tmp/php-fpm.conf --allow-to-run-as-root &
PHP_FPM_PID=$!

# Warte kurz, damit PHP-FPM startet
sleep 3

# Prüfe ob PHP-FPM läuft
if ! kill -0 $PHP_FPM_PID 2>/dev/null; then
    echo "ERROR: PHP-FPM failed to start!"
    cat /tmp/logs/php-fpm.log 2>/dev/null || echo "No PHP-FPM log available"
    exit 1
fi

echo "PHP-FPM started successfully (PID: $PHP_FPM_PID)"

# Funktion für graceful shutdown
cleanup() {
    echo "Shutting down services..."
    kill $PHP_FPM_PID 2>/dev/null
    exit 0
}

# Signal Handler für graceful shutdown
trap cleanup SIGTERM SIGINT

# Starte Nginx im Vordergrund
echo "Starting Nginx on port $PORT..."
echo "Application should be available at: http://localhost:$PORT"

# Verwende exec um sicherzustellen, dass Nginx PID 1 wird
exec nginx -c /tmp/nginx.conf -g "daemon off;"
