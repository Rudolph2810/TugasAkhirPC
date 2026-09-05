# =========================
# Stage 1: Build Frontend
# =========================
FROM node:22-alpine AS frontend

WORKDIR /app

# Copy dependency frontend
COPY package*.json ./

# Install dependency
RUN npm ci

# Copy file yang dibutuhkan Vite
COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js ./

# Build Vite
RUN npm run build

# Pastikan manifest berhasil dibuat
RUN echo "=== VITE BUILD OUTPUT ===" \
    && ls -la /app/public/build \
    && echo "=== MANIFEST ===" \
    && cat /app/public/build/manifest.json


# =========================
# Stage 2: Laravel + FrankenPHP
# =========================
FROM dunglas/frankenphp:php8.3

# Install PHP extensions
RUN install-php-extensions \
    gd \
    zip \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app


# =========================
# Install Laravel dependencies
# =========================

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction


# =========================
# Copy Laravel application
# =========================

COPY . .


# =========================
# Copy Vite build
# =========================

COPY --from=frontend /app/public/build ./public/build

# Pastikan manifest ada di image Laravel
RUN echo "=== LARAVEL VITE MANIFEST ===" \
    && ls -la /app/public/build \
    && test -f /app/public/build/manifest.json


# =========================
# Laravel storage permissions
# =========================

RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache


# =========================
# FrankenPHP / Caddy
# =========================

COPY Caddyfile /etc/frankenphp/Caddyfile

EXPOSE 8080

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]