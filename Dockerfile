# =========================
# Stage 1: Build Vite
# =========================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js ./

RUN npm run build


# =========================
# Stage 2: Laravel + FrankenPHP
# =========================
FROM dunglas/frankenphp:php8.3

RUN install-php-extensions \
    gd \
    zip \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction

COPY . .

# Ambil hasil build Vite dari stage frontend
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY Caddyfile /etc/frankenphp/Caddyfile

EXPOSE 8080

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]