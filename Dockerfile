FROM php:8.2-apache

# Doinstaluj potřebné knihovny pro PostgreSQL a XSLT
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libxslt1-dev \
    && docker-php-ext-install pdo pdo_pgsql xsl

COPY php.ini /usr/local/etc/php/
