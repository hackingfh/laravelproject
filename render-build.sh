#!/usr/bin/env bash
# exit on error
set -o errexit

# Install composer dependencies
composer install --no-dev --optimize-autoloader

# Install and build npm assets
npm install
npm run build

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migrate the database (SQLite)
# Note: In production, you might want to handle this differently
# but for initial setup, we ensure the db exists.
touch database/database.sqlite
php artisan migrate --force
