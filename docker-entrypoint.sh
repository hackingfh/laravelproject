#!/usr/bin/env bash
set -e

echo "=== Docker Entrypoint Starting ==="

# -------------------------------------------------------
# DEBUG: Dump ALL environment variables to diagnose Render injection
# -------------------------------------------------------
echo "=== ALL ENVIRONMENT VARIABLES ==="
env | sort
echo "================================="

# -------------------------------------------------------
# Bridge Render's DATABASE_URL to Laravel's individual DB_* variables
# Render provides DATABASE_URL as:
#   postgres://user:password@host:port/dbname
# or postgresql://user:password@host:port/dbname
# Laravel needs: DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# -------------------------------------------------------
if [ -n "$DATABASE_URL" ]; then
    echo "✅ DATABASE_URL found, parsing into DB_* variables..."

    # Always force pgsql driver
    export DB_CONNECTION="pgsql"

    # Parse the URL components
    # Remove the postgres:// or postgresql:// prefix
    DB_URL_STRIPPED="${DATABASE_URL#postgres://}"
    DB_URL_STRIPPED="${DB_URL_STRIPPED#postgresql://}"

    # Extract user:password@host:port/dbname
    USERPASS="${DB_URL_STRIPPED%%@*}"
    HOSTPORTDB="${DB_URL_STRIPPED#*@}"

    export DB_USERNAME="${USERPASS%%:*}"
    export DB_PASSWORD="${USERPASS#*:}"

    HOSTPORT="${HOSTPORTDB%%/*}"
    export DB_DATABASE="${HOSTPORTDB#*/}"

    export DB_HOST="${HOSTPORT%%:*}"
    export DB_PORT="${HOSTPORT#*:}"

    # Also set DB_URL for Laravel's url config key
    export DB_URL="$DATABASE_URL"

    echo "✅ Parsed DATABASE_URL successfully"
else
    echo "⚠️ DATABASE_URL not found!"
    echo "Using individual DB_* variables if set..."
    # Default to pgsql if DB_CONNECTION not set
    export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
fi

# Debug: show resolved DB config
echo "=== RESOLVED DB CONFIG ==="
echo "DB_CONNECTION=${DB_CONNECTION:-not set}"
echo "DB_HOST=${DB_HOST:-not set}"
echo "DB_PORT=${DB_PORT:-not set}"
echo "DB_DATABASE=${DB_DATABASE:-not set}"
echo "DB_USERNAME=${DB_USERNAME:-not set}"
echo "DB_PASSWORD=****"
echo "=========================="

# Verify pdo_pgsql extension is loaded
php -m | grep pdo_pgsql && echo "✅ pdo_pgsql loaded" || echo "❌ pdo_pgsql NOT loaded"

# Clear stale config cache then rebuild with current env vars
echo "Clearing all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear 2>/dev/null || true

# Re-cache with the resolved environment variables (important for production performance)
echo "Re-caching config, routes and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configure Apache to listen on Render's PORT (default 10000)
if [ -n "$PORT" ]; then
    echo "Configuring Apache to listen on PORT=$PORT..."
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/" /etc/apache2/sites-available/000-default.conf
fi

# Test database connection — stop deployment if DB is unreachable
echo "Testing database connection..."
php artisan db:show 2>&1 || { echo "❌ Database connection FAILED — aborting deployment."; exit 1; }

echo "Running migrations..."
php artisan migrate --force --ansi 2>&1 || { echo "❌ Migrations FAILED — aborting deployment."; exit 1; }

echo "=== Docker Entrypoint Complete ==="

# Execute the main command (Apache)
exec "$@"
