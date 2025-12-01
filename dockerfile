# PHP Alpine builder for dependencies
FROM php:8.4-alpine AS laravel-builder

# Upgrade
RUN apk update && apk upgrade

WORKDIR /app

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install required PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_pgsql \
    intl \
    zip \
    opcache \
    pcntl

# Install Laravel dependencies (without dev)
RUN composer install --no-dev --optimize-autoloader

# Node.js Alpine Builder
FROM node:18-alpine AS node-builder

# Upgrade
RUN apk update && apk upgrade

# Vite ARGS
ARG VITE_APP_NAME
ARG VITE_CDN_URL

WORKDIR /app

# Copy project files
COPY . .

# Copy the vendor directory from the Laravel builder (needed for Tailwind preset)
COPY --from=laravel-builder /app/vendor /app/vendor

# Install Node dependencies
RUN npm install

# Build Vite
RUN npm run build

# Final FrankenPHP Image
FROM dunglas/frankenphp:1.9-php8.4-alpine AS final

# Domain Server Name
ENV SERVER_NAME=garamm.dev

LABEL maintainer="ProblematicToucan <gamal.aziz1000@gmail.com>"
LABEL org.opencontainers.image.title="Filament Portfolio"
LABEL org.opencontainers.image.description="Production-ready Filament Portfolio with Octane"
LABEL org.opencontainers.image.source=https://github.com/ProblematicToucan/fullstack-porto
LABEL org.opencontainers.image.licenses=MIT

# Upgrade
RUN apk update && apk upgrade

# Install node runtime
RUN apk add --no-cache nodejs

# Production
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Copy custom PHP settings
COPY docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Set working directory
WORKDIR /app

# Install required PHP extensions
RUN install-php-extensions \
    pdo_pgsql \
    intl \
    pcntl

# Copy built Laravel project
COPY --from=laravel-builder /app /app
COPY --from=node-builder /app/public /app/public
COPY --from=node-builder /app/node_modules /app/node_modules
COPY --from=node-builder /app/bootstrap/ssr /app/bootstrap/ssr

# Make the entrypoint script executable
RUN chmod +x ./run

# Expose Laravel Octane port
EXPOSE 8000

# Set the entrypoint
ENTRYPOINT ["./run"]

# Healthcheck
HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=18 CMD php artisan octane:status || exit 1
