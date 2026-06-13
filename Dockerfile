# Stage 1: Build frontend assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Production PHP environment
FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application source code
COPY . .

# Copy compiled frontend assets from the node-builder stage
COPY --from=node-builder /app/public/build ./public/build

# Install production PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Create necessary framework directories and set broad write permissions
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache storage/app/public \
    && chown -R www-data:www-data /var/www \
    && chmod -R 777 storage bootstrap/cache

# Make entrypoint script executable
RUN chmod +x docker-entrypoint.sh

# Expose port and start
ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
