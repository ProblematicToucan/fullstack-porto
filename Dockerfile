# PHP Alpine builder for dependencies
FROM php:8.4-alpine AS laravel-builder

# Upgrade
RUN apk update && apk upgrade

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable PHP extension installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install required PHP extensions
RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    intl \
    zip \
    opcache


# Set the working directory
WORKDIR /app

# Copy the PHP files of your project in the public directory
COPY . /app

# Install Laravel dependencies (without dev)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Node.js Alpine Builder
FROM node:24-alpine AS node-builder

# Upgrade
RUN apk update && apk upgrade

# Set the working directory
WORKDIR /app

# Copy project files
COPY . /app

# Copy the vendor directory from the Laravel builder (needed for Tailwind preset)
COPY --from=laravel-builder /app/vendor /app/vendor

# Install Node dependencies
RUN npm install

# Build Vite
RUN npm run build

# Final FrankenPHP Image
FROM dunglas/frankenphp:1.12.1-php8.4-alpine AS final

# Set the server name
ENV SERVER_NAME=garamm.dev

# Production
RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

LABEL maintainer="ProblematicToucan <gamal.aziz1000@gmail.com>"
LABEL org.opencontainers.image.title="Filament Portfolio"
LABEL org.opencontainers.image.description="Production Filament portfolio: FrankenPHP Octane and queue workers under supervisord"
LABEL org.opencontainers.image.source=https://github.com/ProblematicToucan/fullstack-porto
LABEL org.opencontainers.image.licenses=MIT

# Install required PHP extensions
RUN install-php-extensions \
    pdo_pgsql \
    intl \
    pcntl

# Install supervisor for process management
RUN apk add --no-cache supervisor

# Workdir
WORKDIR /app

# Copy built Laravel project from builder
COPY --from=laravel-builder /app /app
COPY --from=node-builder /app/public /app/public

# Create non-root user (fixed UID/GID for consistency)
RUN addgroup -g 1000 app && \
    adduser -u 1000 -G app -s /bin/sh -D app

# Ensure storage and cache are writable
RUN chown -R app:app /app

# Make the entrypoint script executable
RUN chmod +x /app/run

# Switch to non-root user
USER app

# Entrypoint
ENTRYPOINT ["/app/run"]

# Healthcheck
HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=18 CMD php artisan octane:status || exit 1