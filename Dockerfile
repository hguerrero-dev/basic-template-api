# Dockerfile para Laravel + PHP + Composer
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    libwebp \
    libwebp-dev \
    freetype \
    freetype-dev \
    icu-dev \
    icu \
    icu-libs \
    icu-data \
    libxml2 \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    git \
    curl \
    openssl \
    shadow \
    nodejs \
    npm \
    build-base

# Instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd intl xml mbstring zip opcache

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copia el código fuente
WORKDIR /var/www
COPY . .

# Instala dependencias de Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www

# Exponer el puerto para servir la app
EXPOSE 9000

CMD ["php-fpm"]
