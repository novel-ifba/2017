# CodeIgniter 3.1.0 nao roda em PHP 8.x. 7.4 e a versao mais nova compativel.
FROM php:7.4-apache

RUN docker-php-ext-install mysqli \
 && a2enmod rewrite

COPY docker/apache/novel.conf /etc/apache2/conf-available/novel.conf
RUN a2enconf novel

COPY docker/php/novel.ini /usr/local/etc/php/conf.d/novel.ini
RUN mkdir -p /var/lib/php/sessions \
 && chown www-data:www-data /var/lib/php/sessions

WORKDIR /var/www/html
