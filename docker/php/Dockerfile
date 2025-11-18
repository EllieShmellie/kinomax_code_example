FROM php:8.2-fpm

ARG PUID=1000
ARG PGID=1000

# Install system dependencies and PHP extensions required by Yii2 + PostgreSQL
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        rsync \
        libpq-dev \
        cron \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Add Composer from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupmod -g ${PGID} www-data \
    && usermod -u ${PUID} www-data

WORKDIR /var/www/html

# Copy cron schedule
COPY crontab.txt /etc/cron.d/premiere-cron
RUN chmod 0644 /etc/cron.d/premiere-cron \
    && crontab /etc/cron.d/premiere-cron

# Expose a startup script that launches cron + php-fpm
COPY start.sh /usr/local/bin/container-start
RUN chmod +x /usr/local/bin/container-start
