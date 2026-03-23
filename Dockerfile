FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip git curl libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
