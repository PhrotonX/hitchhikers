#!/bin/sh

set -e

if [ ! -d "/var/www/storage/logs" ]; then
    mkdir -p /var/www/storage/logs
fi

if [ ! -d "/var/www/storage/framework/cache" ]; then
    mkdir -p /var/www/storage/framework/cache
fi

if [ ! -d "/var/www/storage/framework/sessions" ]; then
    mkdir -p /var/www/storage/framework/sessions
fi

if [ ! -d "/var/www/storage/framework/views" ]; then
    mkdir -p /var/www/storage/framework/views
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R ug+rwX /var/www/storage /var/www/bootstrap/cache

exec "$@"