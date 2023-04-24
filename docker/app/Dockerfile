FROM php:8.1.9-fpm

#Update!
RUN apt-get update

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Install zip+icu dev libs + pgsql dev support
RUN apt-get install libzip-dev zip libicu-dev libpq-dev -y

#xdebug
RUN apt-get update && apt-get install -y wget git unzip \
    && pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug

#Installing GD in Docker for dynamically manipulating images
RUN apt-get update -y && apt-get install -y libpng-dev
RUN docker-php-ext-install gd

#spatie/image
RUN docker-php-ext-install exif

#Install PHP extensions zip and intl (intl requires to be configured)
RUN docker-php-ext-install zip && docker-php-ext-configure intl && docker-php-ext-install intl && docker-php-ext-install sockets

# Add MySQL and Postgres/pgsql support
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql pgsql

#for horizon queue
RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl

RUN apt-get install -y nodejs npm

#Required for htaccess rewrite rules
#RUN a2enmod rewrite
COPY ./docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/sitemap_generator
