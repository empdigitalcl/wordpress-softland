FROM php:7.4.1-fpm

# COPY php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# install Node.js
COPY --from=node:10.22 /usr/local/bin /usr/local/bin
COPY --from=node:10.22 /usr/local/lib /usr/local/lib

RUN apt-get update && \
    apt-get -y install \
    git \
    vim

RUN apt-get install -y build-essential 
RUN apt-get install -y libfreetype6-dev
RUN apt-get install -y libjpeg62-turbo-dev
RUN apt-get install -y libmcrypt-dev
RUN apt-get install -y libpng-dev
RUN apt-get install -y curl
RUN apt-get install -y libcurl4
RUN apt-get install -y libcurl4-openssl-dev
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y libicu-dev
RUN apt-get install -y libmemcached-dev
RUN apt-get install -y memcached
RUN apt-get install -y default-mysql-client
RUN apt-get install -y libmagickwand-dev
RUN apt-get install -y unzip
RUN apt-get install -y libzip-dev
RUN apt-get install -y zip

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd
RUN docker-php-ext-configure mysqli --with-mysqli=mysqlnd
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure zip

RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) zip

RUN apt-get install -y libxml2-dev
RUN docker-php-ext-install soap
RUN docker-php-ext-install bcmath

WORKDIR /var/www/html