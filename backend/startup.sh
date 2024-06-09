#!/bin/bash

if ! php /var/www/artisan migrate; then
    echo "Migration failed!"
    exit 1
fi

if ! php /var/www/artisan db:seed; then
    echo "Seeding failed!"
    exit 1
fi

supervisord -c /etc/supervisor/conf.d/worker.conf && php-fpm
