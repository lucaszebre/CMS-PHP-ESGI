# Utilise l'image officielle PHP + Apache
FROM php:8.2-apache

# Serve the app from /public (front controller pattern)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Installer les dépendances nécessaires pour PostgreSQL et activer modules
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    zip \
    opcache

# Point Apache configs to /public and allow .htaccess rewrites
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# Copier un php.ini personnalisé si besoin (monté via docker-compose)
# WORKDIR /var/www/html

# Permettre à Apache d'écrire sur le dossier (utile pour uploads / sessions)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
