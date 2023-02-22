# Base
FROM php:8.1.16-apache-bullseye

# Environment variables
ENV WEB_FOLDER=/var/www/html

# Define workdir
WORKDIR ${WEB_FOLDER}

# Softwares
RUN apt-get update && apt-get install -y \
    acl \
    apt-utils \
    build-essential \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpq-dev \
    libpng-dev \
    libssh-dev \
    libwebp-dev \
    libzip-dev \
    nano \
    pkg-config \
    unzip \
    wget \
    zip

# Apache modules
RUN a2enmod \
    rewrite \
    expires \
    headers
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Extension PHP
RUN docker-php-ext-install \
    gd \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    bcmath \
    zip \
    exif \
    opcache \
    iconv \
    sockets \
    pcntl \
    intl

# Add php.ini custom config
COPY ./tools/docker/config/php.ini /usr/local/etc/php/

# Add vhost
COPY ./tools/docker/config/default-vhost.conf /etc/apache2/sites-available/
RUN a2dissite 000-default.conf \
    && a2ensite default-vhost.conf 

# Composer
COPY ./tools/scripts/install-composer.sh /tmp
RUN bash /tmp/install-composer.sh

# APCU
RUN pear config-set http_proxy "${http_proxy}" \
    && pecl channel-update pecl.php.net \
    && pecl install apcu
RUN echo "extension=apcu.so\napc.enable_cli=on" > /usr/local/etc/php/conf.d/apcu.ini

# X-Debug
RUN pecl install xdebug-3.2.0 \
    && echo 'xdebug.mode=develop,debug,coverage' >> /usr/local/etc/php/php.ini \
    && echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/php.ini \
    && docker-php-ext-enable xdebug

# Add Apache user
RUN useradd -r --create-home -u 998 1000 \
    && adduser 1000 root \
    && adduser 1000 www-data \
    && echo '%998 ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

# Aliases
RUN echo "alias arc='service apache2 reload'" >> /root/.bashrc \
    && echo "alias epi='nano /usr/local/etc/php/php.ini'" >> /root/.bashrc

# Clean the mess
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add files
#COPY --chown=www-data:www-data . ${WEB_FOLDER}

# Add Entrypoint
COPY ./tools/scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Run
CMD ["/usr/local/bin/docker-entrypoint.sh"]
