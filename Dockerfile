FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    npm \
  && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip gd \
  && rm -rf /var/lib/apt/lists/*

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader || true

# Copy node package files and build assets
COPY package*.json ./
RUN npm ci --silent || true
COPY vite.config.js ./

# Copy application source
COPY . .

# Build frontend (if project has build scripts)
RUN if [ -f package.json ]; then npm run build --silent || true; fi

# Set permissions for storage & cache
RUN if [ -d storage ]; then chown -R www-data:www-data storage bootstrap/cache || true; fi

# Railway provides $PORT; default to 8080
ENV PORT=8080
EXPOSE 8080

# Use artisan serve so container listens on the provided PORT
CMD ["sh", "-c", "php artisan key:generate --ansi || true; php artisan config:cache || true; php artisan serve --host=0.0.0.0 --port=${PORT}"]
