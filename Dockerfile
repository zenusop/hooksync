FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y git zip unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy project files
COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install

EXPOSE 80
