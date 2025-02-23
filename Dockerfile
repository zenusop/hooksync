# Use an official PHP-Apache base image
FROM php:8.1-apache

# Enable any PHP extensions you need, for example:
RUN docker-php-ext-install pdo pdo_mysql

# Copy your application code into the container
COPY . /var/www/html

# Expose port 80 to match Render’s required port
EXPOSE 80

# By default, the official php:apache image
# automatically runs Apache in the foreground,
# so no CMD line is needed unless you want to modify the defaults
