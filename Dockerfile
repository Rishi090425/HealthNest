FROM php:8.2-apache

# Install system dependencies + SQLite + PostgreSQL + dos2unix
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev \
    sqlite3 \
    dos2unix \
    libpq-dev \
    && docker-php-ext-install gd pdo pdo_sqlite pdo_mysql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Copy Apache virtual host configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Node.js, compile assets, and remove Node.js to keep image clean
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build \
    && rm -rf node_modules \
    && apt-get remove -y nodejs \
    && apt-get autoremove -y \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer and dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-interaction

# Create .env from .env.example if .env is missing, then set production values
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php artisan key:generate --force \
    && sed -i 's/APP_ENV=local/APP_ENV=production/' .env \
    && sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env \
    && sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env \
    && sed -i 's/^DB_HOST=/#DB_HOST=/' .env \
    && sed -i 's/^DB_PORT=/#DB_PORT=/' .env \
    && sed -i 's/^DB_DATABASE=/#DB_DATABASE=/' .env \
    && sed -i 's/^DB_USERNAME=/#DB_USERNAME=/' .env \
    && sed -i 's/^DB_PASSWORD=/#DB_PASSWORD=/' .env

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Create SQLite database file
RUN touch /var/www/html/database/database.sqlite \
    && chown www-data:www-data /var/www/html/database/database.sqlite \
    && chmod 664 /var/www/html/database/database.sqlite

# Expose port 80 (Render/Railway will map $PORT to this via env)
EXPOSE 80

# Inline startup: configure Apache to listen on $PORT, run migrations, and start
CMD ["/bin/bash", "-c", \
    "PORT=${PORT:-80} && \
     printf 'Listen %s\\n<IfModule ssl_module>\\n  Listen 443\\n</IfModule>\\n' \"$PORT\" > /etc/apache2/ports.conf && \
     sed -i \"s/<VirtualHost \\*:[0-9]*>/<VirtualHost *:${PORT}>/\" /etc/apache2/sites-enabled/000-default.conf && \
     a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork 2>/dev/null; \
     if [ -z \"\$APP_KEY\" ] && ! grep -q \"^APP_KEY=base64\" .env; then php artisan key:generate --force; fi && \
     php artisan config:clear && \
     php artisan cache:clear && \
     php artisan migrate --force && \
     chown -R www-data:www-data /var/www/html/database /var/www/html/storage && \
     chmod -R 775 /var/www/html/database /var/www/html/storage && \
     apache2-foreground"]
