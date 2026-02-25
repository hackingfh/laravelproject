#!/usr/bin/env bash
# exit on error
set -o errexit

echo "--- Starting Build Process ---"

# Verify pdo_pgsql extension
echo "Checking pdo_pgsql extension..."
php -m | grep pdo_pgsql && echo "✅ pdo_pgsql OK" || (echo "❌ pdo_pgsql manquant!" && exit 1)

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

# Clear ALL caches first (removes stale MySQL config)
echo "Clearing all Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear || true

# Now rebuild caches with current env vars (DB_CONNECTION=pgsql from Render)
echo "Rebuilding caches with current configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Debug: show which DB connection is active
echo "Active DB connection:"
php artisan tinker --execute="echo 'DB_CONNECTION=' . config('database.default');" || true

# Run migrations against PostgreSQL
echo "Running database migrations..."
php artisan migrate --force --ansi

echo "--- Build Process Completed ---"
