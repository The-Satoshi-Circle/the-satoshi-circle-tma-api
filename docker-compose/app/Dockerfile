FROM php:8.3-fpm

# Set non-interactive frontend
ENV DEBIAN_FRONTEND=noninteractive

# Install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
    curl git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev mariadb-client \
    gnupg2 ca-certificates software-properties-common lsb-release \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath exif pcntl gd

# Install Node.js 22 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app code
COPY . .

# Install dependencies & build frontend
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && php artisan storage:link

RUN npm run build

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
