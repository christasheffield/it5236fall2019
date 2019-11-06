FROM php:7.2-apache

# Install the PDO and PDO MySQL drivers
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy the webapp and api
COPY webapp/ /var/www/html/
COPY api/ /var/www/html/api/
COPY phpMyAdmin/ /var/www/html/phpMyAdmin/
COPY ddb/ /var/www/html/ddb/
COPY credentials.php /var/www/html/api/
