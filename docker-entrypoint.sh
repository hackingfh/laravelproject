#!/usr/bin/env bash
set -e

echo "=== Docker Entrypoint Starting ==="

# Show current DB configuration for debugging
echo "DB_CONNECTION=${DB_CONNECTION:-not set}"
echo "DB_HOST=${DB_HOST:-not set}"
echo "DB_PORT=${DB_PORT:-not set}"
echo "DB_DATABASE=${DB_DATABASE:-not set}"

# Verify pdo_pgsql extension is loaded
php -m | grep pdo_pgsql && echo "✅ pdo_pgsql loaded" || echo "⚠️ pdo_pgsql NOT loaded"

# Clear stale config cache, then re-cache with runtime env vars
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Handle SQLite vs PostgreSQL
if [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "Using SQLite database..."
    mkdir -p database
    touch database/database.sqlite
    chown -R www-data:www-data database
    chmod -R 775 database
else
    echo "Using $DB_CONNECTION database..."
    echo "Testing database connection..."
    php artisan db:show || echo "⚠️ Database connection test failed, continuing anyway..."
fi

echo "Running migrations..."
php artisan migrate --force --ansi

echo "=== Docker Entrypoint Complete ==="

# Execute the main command (Apache)
exec "$@"
