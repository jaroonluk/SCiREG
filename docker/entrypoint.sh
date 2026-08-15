#!/bin/bash
set -e

cd /var/www/html

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache

if [ ! -f vendor/autoload.php ]; then
    echo "Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
fi

if [ ! -f public/build/manifest.json ]; then
    echo "Building frontend assets..."
    if [ -f package-lock.json ]; then
        npm ci
    else
        npm install
    fi
    npm run build
fi

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

exec "$@"
