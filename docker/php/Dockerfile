FROM php:8.1.1-fpm-alpine

WORKDIR /var/www/html

# Setup GD extension
RUN apk add --no-cache \
      acl \
      autoconf \
      bash \
      git \
      gcc \
      make \
      g++ \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
      php-sysvsem \
      libzip-dev \
      zip \
      openssh \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/*

RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN rm -rf /var/cache/apk/*

# PHP sysvsem
RUN docker-php-ext-install sysvsem && docker-php-ext-enable sysvsem
# PHP ZIP
RUN docker-php-ext-configure zip && docker-php-ext-install zip

RUN apk add libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip bcmath

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN echo 'alias sf="php bin/console"' >> ~/.bashrc