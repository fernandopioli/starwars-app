#!/bin/sh
set -e

cd /app/app

if [ ! -d "node_modules" ]; then
    echo "Installing Node.js dependencies..."
    npm install
fi

echo "Starting Vite development server with Hot Module Replacement (HMR)..."
exec npm run dev -- --host 0.0.0.0