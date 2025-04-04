#!/bin/bash
set -e

mkdir -p /app/app/database
chmod -R 777 /app/app/database

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