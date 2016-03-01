FROM shubnikofff/php5.5-yii2

MAINTAINER Shubnikov Alexey <shubnikov.av@gmail.com>

COPY . /var/www/html/

RUN mv php.ini /usr/local/etc/php/conf.d/ \
    && mv sites-enabled /etc/apache2/ \
    && chown -R www-data /var/www/html/frontend/runtime \
        /var/www/html/frontend/web/assets \
        /var/www/html/backend/runtime \
        /var/www/html/backend/web/assets