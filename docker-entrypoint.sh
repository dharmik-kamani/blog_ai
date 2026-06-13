#!/bin/sh
set -e

# Ensure storage directories exist
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/app/public

# Clear any existing storage link and recreate it to avoid broken links
if [ -L /var/www/public/storage ] || [ -d /var/www/public/storage ]; then
    echo "Removing existing public/storage directory/link..."
    rm -rf /var/www/public/storage
fi
echo "Creating public/storage symlink..."
php artisan storage:link --force

# Run migrations if database connection is set up
# If DB_CONNECTION or DB_URL is configured
if [ -n "$DB_URL" ] || [ -n "$DB_HOST" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
else
    echo "No database configuration detected. Skipping migrations."
fi

# Optimize Laravel application for production
echo "Caching configurations, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Retrieve port from env, default to 8080
PORT=${PORT:-8080}

echo "Starting Laravel via Artisan on host 0.0.0.0, port $PORT..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
