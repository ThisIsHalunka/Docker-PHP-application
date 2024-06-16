FROM php:7.4-fpm-alpine

# Встановлення необхідних пакетів та залежностей
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev linux-headers \
    && apk add --no-cache curl

# Завантаження та розпакування Xdebug
RUN curl -Lso /tmp/xdebug.tgz https://xdebug.org/files/xdebug-2.9.8.tgz \
    && tar -xzf /tmp/xdebug.tgz -C /tmp \
    && rm /tmp/xdebug.tgz

# Компіляція та встановлення Xdebug
RUN cd /tmp/xdebug-2.9.8 \
    && phpize \
    && ./configure --enable-xdebug \
    && make \
    && make install \
    && rm -r /tmp/xdebug-2.9.8

# Налаштування Xdebug
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN apk --no-cache add git unzip

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копіюємо файли проєкту до директорії /var/www/html
COPY . /var/www/html

# Встановлюємо залежності через Composer
WORKDIR /var/www/html
RUN composer install
# Видалення тимчасових залежностей
RUN apk del .build-deps

LABEL authors="Bohdan Halunka"
