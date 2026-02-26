FROM php:8.5-fpm-alpine AS base
ARG xdebug=false
RUN apk add --no-cache $PHPIZE_DEPS libzip-dev linux-headers \
    && docker-php-ext-install zip pdo_mysql \
    && pecl install redis && docker-php-ext-enable redis \
    && pecl install igbinary && docker-php-ext-enable igbinary \
    && if [ "$xdebug" = "true" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi \
    && apk del $PHPIZE_DEPS linux-headers && rm -rf /tmp/pear

FROM base AS development
COPY --from=composer:2.9 /usr/bin/composer /usr/local/bin/composer

FROM base AS production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY . .
COPY --from=vendor /app/vendor ./vendor
USER www-data

FROM composer:2.9 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

