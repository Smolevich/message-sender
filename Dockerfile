FROM php:fpm-alpine

ENV PHP_RUN_DEPS curl \
    libjpeg-turbo-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    freetype-dev \
    openssl-dev \
    libmcrypt-dev \
    autoconf

ENV PHPIZE_DEPS g++ \
		gcc \
        make
RUN set -xe \
	&& apk add --no-cache --virtual .build-deps \
	$PHPIZE_DEPS

RUN set -xe && \
    apk add --no-cache --virtual .php-run-deps $PHP_RUN_DEPS 

RUN docker-php-ext-install soap \
    mysqli \
    pdo_mysql \
    gd \
    zip

RUN pecl channel-update pecl.php.net \
    && pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis  

WORKDIR /var/www/html