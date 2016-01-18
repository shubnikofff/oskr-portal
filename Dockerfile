FROM shubnikofff/php:yii2

MAINTAINER Shubnikov Alexey <shubnikov.av@gmail.com>

COPY . /var/www/html/
COPY common/config/php.ini /usr/local/etc/php/conf.d/
COPY common/config/sites-enabled/ /etc/apache2/sites-enabled/

RUN chown -R www-data /var/www/html/frontend/runtime /var/www/html/frontend/web/assets /var/www/html/backend/runtime /var/www/html/backend/web/assets