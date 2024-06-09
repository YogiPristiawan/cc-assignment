FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libonig-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    libxml2 \
    zip \
    unzip \
    libzip-dev \
    libxml2-dev \
    libpq-dev \
    supervisor
RUN docker-php-ext-install \
    curl \
    mbstring \
    xml \
    zip \
    pdo_pgsql \
    pgsql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.7.6

WORKDIR  /var/www

COPY . .

RUN composer install

COPY worker.conf /etc/supervisor/conf.d/worker.conf
COPY startup.sh /usr/local/bin/startup.sh

RUN chmod +x /usr/local/bin/startup.sh
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

ENTRYPOINT ["/usr/local/bin/startup.sh"]

