FROM php:apache

RUN apt-get update
RUN apt-get install -y apt-utils vim curl sqlite3
RUN pecl install xdebug

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
RUN a2enmod headers

ADD ./config/000-default.conf /etc/apache2/sites-enabled/
ADD ./config/php.ini /usr/local/etc/php/