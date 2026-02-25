#!/usr/bin/env bash
# exit on error
set -o errexit

echo "--- Starting Build Process ---"

# Ensure storage directories exist
echo "Ensuring storage directories..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Install composer dependencies
echo "Installing Composer dependencies..."
if [ -f "composer.json" ]; then
    composer install --no-interaction --no-dev --optimize-autoloader
else
    echo "Error: composer.json not found"
    exit 1
fi

# Install and build npm assets
if command -v npm &> /dev/null; then
    echo "Installing and building NPM assets..."
    npm install
    npm run build
else
    echo "NPM not found, skipping asset build"
fi

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
