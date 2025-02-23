FROM php:8.1-apache

# Install any extra packages you might need
RUN apt-get update && apt-get install -y \
    zip \
    unzip

# Copy all project files into the container
COPY . /var/www/html

# Make /var/www/html the working directory
WORKDIR /var/www/html

# Expose port 80 for Render
EXPOSE 80
