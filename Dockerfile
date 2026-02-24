FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl \
    libzip-dev libpng-dev libonig-dev libicu-dev \
    npm \
  && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip gd intl \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --prefer-dist --no-interaction --no-progress --optimize-autoloader

COPY . .

RUN if [ -f package.json ]; then npm install && npm run build; fi

ENV PORT=8080
EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=${PORT}