#!/usr/bin/env bash
set -e

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure database exists and is migrated
mkdir -p database
touch database/database.sqlite
chown -R www-data:www-data database
chmod -R 775 database

echo "Running migrations..."
php artisan migrate --force --ansi

# Execute the main command (Apache)
exec "$@"
