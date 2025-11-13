#!/bin/bash

# Azure App Service Deployment Script for CakePHP

set -e

echo "Starting CakePHP deployment..."

# Install Composer dependencies
echo "Installing Composer dependencies..."
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --quiet
php -r "unlink('composer-setup.php');"

# Install production dependencies
./composer.phar install --no-dev --optimize-autoloader --no-interaction

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 $DEPLOYMENT_TARGET
chmod -R 777 $DEPLOYMENT_TARGET/tmp
chmod -R 777 $DEPLOYMENT_TARGET/logs

# Clear CakePHP cache
echo "Clearing cache..."
if [ -d "$DEPLOYMENT_TARGET/tmp/cache" ]; then
    rm -rf $DEPLOYMENT_TARGET/tmp/cache/*
fi

# Run database migrations (if available)
if [ -f "$DEPLOYMENT_TARGET/bin/cake.php" ]; then
    echo "Running database migrations..."
    php $DEPLOYMENT_TARGET/bin/cake.php migrations migrate --no-interaction || echo "No migrations to run"
fi

echo "Deployment completed successfully!"
