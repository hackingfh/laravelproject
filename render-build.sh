#!/usr/bin/env bash
# exit on error
set -o errexit

echo "--- Starting Build Process ---"

# Install composer dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --no-dev --optimize-autoloader

# Install and build npm assets
echo "Installing and building NPM assets..."
npm install
npm run build

# Clear and cache
echo "Clearing and caching Laravel components..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migrate the database (SQLite)
echo "Setting up SQLite database..."
mkdir -p database
touch database/database.sqlite
php artisan migrate --force --ansi

echo "--- Build Process Completed ---"
