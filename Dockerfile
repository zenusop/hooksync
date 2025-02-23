FROM php:8.1-apache

# Install any needed packages
RUN apt-get update && apt-get install -y \
    zip \
    unzip

# Copy project files into the container
COPY . /var/www/html

# Set the correct ownership and permissions
RUN chown -R www-data:www-data /var/www/html/data
RUN chmod -R 775 /var/www/html/data

WORKDIR /var/www/html

EXPOSE 80
