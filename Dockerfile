FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
        libpq-dev libpng-dev libwebp-dev libjpeg62-turbo-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pgsql gd

WORKDIR /var/www/html

COPY . .

EXPOSE $PORT

CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t public public/index.php"]
