#!/usr/bin/env bash

echo "Installing dependencies..."
composer install --no-dev --working-dir=/var/www/html

echo "Clearing cache..."
php artisan optimize:clear

echo "Caching configuration..."
php artisan config:cache

echo "Running migrations..."
php artisan migrate --force