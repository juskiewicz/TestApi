FROM php:7.3-fpm
ARG TIMEZONE

MAINTAINER grafiko

RUN apt-get update \
    && apt-get install -y \
    openssl \
    git \
    unzip \
    libpq-dev \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo pdo_pgsql \
    && docker-php-ext-install opcache \
    && docker-php-ext-enable opcache \
    && echo 'alias sf="php app/console"' >> ~/.bashrc && echo 'alias sf3="php bin/console"' >> ~/.bashrc
# Type docker-php-ext-install to see available extensions
RUN printf "\
    upload_max_filesize=4G\n\
    post_max_size=4G\n\
    " > /usr/local/etc/php/php.ini