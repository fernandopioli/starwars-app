#!/bin/bash
set -e

cd /app/app

# Ensure proper permissions
mkdir -p database
chmod -R 777 database

# Always install PHP dependencies first
echo "Installing/updating PHP dependencies..."
composer install --no-interaction --optimize-autoloader

echo "Starting scheduler..."

(
  while true; do
    echo "[$(date)] Scheduler running..."
    cd /app/app && php artisan schedule:run
    echo "[$(date)] Scheduler running, waiting 60 seconds..."
    sleep 60
  done
) &

echo "[$(date)] Starting queue worker..."
cd /app/app && php artisan queue:work --tries=3 