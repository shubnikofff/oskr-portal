FROM shubnikofff/php-apache

MAINTAINER Shubnikov Alexey <shubnikov.av@gmail.com>

COPY . /var/www/html/

RUN mv php.ini /usr/local/etc/php/conf.d/ \
    && mv sites-enabled/ /etc/apache2/ \
    && composer install --no-dev \
    && php init --env=Production --overwrite=All