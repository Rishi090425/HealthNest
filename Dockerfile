FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libsqlite3-dev \
    && docker-php-ext-install gd pdo pdo_sqlite

# Set working directory
WORKDIR /var/www/html

# Copy files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chmod -R 775 storage bootstrap/cache database
RUN chown -R www-data:www-data storage bootstrap/cache database

# Create sqlite file
RUN touch database/database.sqlite && chmod 666 database/database.sqlite

# Start script
CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port=$PORT
