FROM php:7.2-apache

# Install the PDO and PDO MySQL drivers
RUN docker-php-ext-install pdo pdo_mysql

# Install nano for easier debugging
RUN apt-get update && apt-get install -y nano

# Copy the webapp and api
COPY webapp/ /var/www/html/
COPY api/ /var/www/html/api/
