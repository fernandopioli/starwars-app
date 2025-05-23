#!/bin/bash
set -e

cd /app/app

# Always install PHP dependencies first
echo "Installing/updating PHP dependencies..."
composer install --no-interaction --optimize-autoloader

# Check if this is first run
if [ ! -f ".env" ]; then
    echo "First run detected, setting up environment..."

    # Ensure database directory exists and is writable
    mkdir -p database
    touch database/database.sqlite
    chmod -R 775 database
    chmod 664 database/database.sqlite
    
    # Copy .env file
    cp .env.example .env
    
    # Generate app key
    php artisan key:generate
    
    # Add Vite manifest handling configuration
    echo "ASSET_URL=" >> .env
    
    # Optimize Laravel
    php artisan optimize
    
    echo "Initial setup completed successfully!"
else
    echo "Environment already set up."
fi

# Create migrations table if it doesn't exist
echo "Setting up database..."

# Run migrations
php artisan migrate --force || true

# Run tests automatically if needed
if [ "$RUN_TESTS" = "true" ]; then
    echo "Running automated tests..."
    php artisan test
fi

# Execute passed command
exec "$@" 