#!/bin/bash
set -e

# Use PORT from environment (Railway sets this), fallback to 8080
APP_PORT="${PORT:-8080}"

echo "Starting Health Nest on port: $APP_PORT"

# Validate that APP_PORT is a valid integer
if ! [[ "$APP_PORT" =~ ^[0-9]+$ ]]; then
    echo "ERROR: Invalid port value: '$APP_PORT'. Defaulting to 8080."
    APP_PORT=8080
fi

# Overwrite ports.conf completely to avoid sed-on-already-replaced content issues
cat > /etc/apache2/ports.conf <<EOF
# If you just change the port or add more ports here, you will likely also
# have to change the VirtualHost statement in
# /etc/apache2/sites-enabled/000-default.conf
Listen ${APP_PORT}

<IfModule ssl_module>
    Listen 443
</IfModule>

<IfModule mod_gnutls.c>
    Listen 443
</IfModule>
EOF

# Update VirtualHost port in the site config
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${APP_PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Set APACHE_RUN_USER/GROUP env vars if not set (needed by apache2-foreground)
export APACHE_RUN_USER="${APACHE_RUN_USER:-www-data}"
export APACHE_RUN_GROUP="${APACHE_RUN_GROUP:-www-data}"

# Run migrations
php artisan migrate --force

# Enable correct MPM
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Start Apache in foreground
exec apache2-foreground
