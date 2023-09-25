# === APACHE ===
FROM php:8.2.6-apache-bullseye as src

ARG UID
ARG GID
ENV UID=${UID}
ENV GID=${GID}

RUN apt update

# Download build dependencies
RUN apt -y install --no-install-recommends \
    rsync ca-certificates openssl openssh-server git tzdata openntpd \
    libxrender-dev fontconfig libc6-dev \
    default-mysql-client gnupg binutils-gold autoconf \
    g++ gcc gnupg build-essential make python3 \
    nodejs npm libfreetype6 libfreetype6-dev libpng-dev libjpeg-dev libpng-dev \
    zlib1g libzip-dev

# PHP extensions
RUN docker-php-ext-install bcmath pdo_mysql gd zip pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl

# Download composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
 && chmod 755 /usr/bin/composer

FROM src as apache

# Setup Unix permissions for Apache `www-data` user
RUN usermod -u ${UID} www-data
RUN groupmod -g ${GID} www-data

RUN usermod -d /var/www www-data
RUN chown -R www-data:www-data /var/www

COPY ./apache2.conf /etc/apache2/apache2.conf
COPY ./sites-enabled /etc/apache2/sites-enabled
COPY ./mods-enabled/php.conf /etc/apache2/mods-enabled/php.conf

RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load