#!/bin/bash

# Fix Apache MPM
a2dismod mpm_event || true
a2enmod mpm_prefork || true

# Fix Port
sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Run Migrations
php artisan migrate --force

# Start Apache
apache2-foreground
