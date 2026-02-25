#!/usr/bin/env bash
set -e

# Cache configuration
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
    # Test connection
    echo "Testing database connection..."
    php artisan db:show || (echo "Database connection failed!" && exit 1)
fi

echo "Running migrations..."
php artisan migrate --force --ansi

# Execute the main command (Apache)
exec "$@"
