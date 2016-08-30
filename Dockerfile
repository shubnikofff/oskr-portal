FROM shubnikofff/php-apache

MAINTAINER Shubnikov Alexey <shubnikov.av@gmail.com>

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

COPY ./sites-enabled/ /etc/apache2/sites-enabled/

COPY . /var/www/html/

RUN php init --env=Production --overwrite=All

ONBUILD ARG composer_git_oauth_token
ONBUILD RUN composer config -g github-oauth.github.com $composer_git_oauth_token \
    && composer install --no-dev
