#!/bin/bash
# Reconfigure Apache port at runtime based on the PORT environment variable provided by Railway
sed -i "s/Listen 80/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT:-8080}>/g" /etc/apache2/sites-available/000-default.conf

# Run migrations
php artisan migrate --force

# Start Apache in foreground
apache2-foreground
