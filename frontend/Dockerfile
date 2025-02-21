FROM php:8.2-apache

# Install necessary dependencies
RUN apt-get update && \
    apt-get install -y nano curl wget libpng-dev libjpeg-dev libonig-dev && \
    docker-php-ext-install mysqli pdo pdo_mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable required Apache modules
RUN a2enmod rewrite headers ssl

# Fix Apache "ServerName" issue
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy project files into the web directory
COPY ./CSE470-PROJECT/ /var/www/html/

# Copy SSL certificates directly into the container
COPY certs/fullchain.pem /etc/ssl/certs/fullchain.pem
COPY certs/privkey.pem /etc/ssl/private/privkey.pem

# Copy Apache configuration
COPY apache-config.conf /etc/apache2/sites-available/krstps.lv.conf
RUN a2ensite krstps.lv.conf

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Optimize Apache configuration for search engine crawlers
RUN echo "ServerTokens Prod" >> /etc/apache2/conf-available/security.conf && \
    echo "ServerSignature Off" >> /etc/apache2/conf-available/security.conf && \
    echo "TraceEnable Off" >> /etc/apache2/conf-available/security.conf && \
    a2enconf security

# Expose ports 80 and 443
EXPOSE 80 443

# Start Apache
CMD ["apache2-foreground"]
