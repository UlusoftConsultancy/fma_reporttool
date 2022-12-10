FROM php:7.4-apache 
RUN docker-php-ext-install mysqli
RUN apt-get update
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip