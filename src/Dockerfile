FROM php:8.3-apache

# Install LibreOffice
RUN apt-get update && \
    apt-get install -y libreoffice && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy application source code
COPY index.php /var/www/html/index.php
COPY functions.php /var/www/html/functions.php
COPY home.html /var/www/html/home.html

# Copy Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80