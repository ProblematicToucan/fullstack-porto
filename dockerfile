# Node js apline builder
FROM node:18-alpine AS node-builder

# Vite ARGS
ARG VITE_APP_NAME
ARG VITE_CDN_URL

WORKDIR /app/public

# Copy project files
COPY . .

# Install node dependency
RUN npm i

# Build vite
RUN npm run build

# Cleanup
RUN rm -rf node_modules

# Php alpine builder
FROM php:8.3-alpine AS laravel-builder

WORKDIR /app/public

# Copy project files
COPY --from=node-builder /app/public /app/public/

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Extension installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# add additional extensions here:
RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    opcache \
    pcntl

# Install laravel dependency
RUN composer install --no-dev --optimize-autoloader

# FrankenPHP with Laravel Octane
FROM dunglas/frankenphp:latest-php8.3-alpine AS final

LABEL maintainer="ProblematicToucan <gamal.aziz1000@gmail.com>"
LABEL org.opencontainers.image.title="Filament Portfolio"
LABEL org.opencontainers.image.description="Production-ready Filament Portfolio with Octane"
LABEL org.opencontainers.image.source=https://github.com/ProblematicToucan/filament-portfolio
LABEL org.opencontainers.image.licenses=MIT

# Set working directory
WORKDIR /app/public

# add additional extensions here:
RUN install-php-extensions \
    pdo_mysql \
    intl \
    pcntl

COPY --from=laravel-builder /app/public /app/public/

# Make the entrypoint script executable
RUN chmod +x ./run

# Expose Laravel Octane port
EXPOSE 8000

# Set the entrypoint
ENTRYPOINT ["./run"]

# Optional health check
HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD php artisan octane:status || exit 1
