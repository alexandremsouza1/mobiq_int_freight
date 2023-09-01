#!/bin/bash

cd /var/www/html && composer install --ignore-platform-reqs --no-interaction
php artisan key:generate
php artisan serve --host 0.0.0.0 --port 8000 --env dev