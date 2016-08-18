FROM php:5-apache

MAINTAINER Shubnikov Alexey <shubnikov.av@gmail.com>

RUN cd /tmp && curl -O  http://download.icu-project.org/files/icu4c/57.1/icu4c-57_1-src.tgz \
    && gunzip -d < icu4c-57_1-src.tgz | tar xvf - \
    && cd icu/source \
    && chmod +x runConfigureICU configure install-sh \
    && ./runConfigureICU Linux/gcc \
    && make -j 100 && make install \
    && rm -rf /tmp/*

RUN apt-get update && apt-get -y install \
	git \
	unzip \
	libssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

#Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \

    && docker-php-ext-install -j$(nproc) \
        gd \
        intl \
        pdo_mysql \
        zip \

#Install Mongo Xdebug
    && pecl install mongo xdebug \
    && docker-php-ext-enable mongo \

#Install Composer
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer global require "fxp/composer-asset-plugin:^1.2.0" \

#Enable Apache rewrite module
    && a2enmod rewrite

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

COPY . /var/www/html/

RUN php init --env=Production --overwrite=All

ONBUILD ARG composer_git_oauth_token
ONBUILD RUN composer config -g github-oauth.github.com $composer_git_oauth_token \
    && composer update --no-dev
