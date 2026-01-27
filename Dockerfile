FROM php:8.5-cli-alpine
COPY --from=composer:2.9 /usr/bin/composer /usr/local/bin/composer
RUN apk add --update --no-cache $PHPIZE_DEPS linux-headers nano zip libzip-dev imagemagick-dev
RUN pecl install imagick && docker-php-ext-enable imagick
RUN docker-php-ext-install zip pdo_mysql ftp

ARG xdebug=false
RUN if [ "$xdebug" = "true" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi;
