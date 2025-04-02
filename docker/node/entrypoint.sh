#!/bin/sh
set -e

cd /app/app

# Check if we need to install dependencies
if [ ! -d "node_modules" ]; then
    echo "Installing Node.js dependencies..."
    npm ci || npm install
fi

# Clean previous build if exists
if [ -d "public/build" ]; then
    echo "Removing previous build..."
    rm -rf public/build
fi

# Create resources directory structure if it doesn't exist
mkdir -p resources/js resources/css

# Build for production
echo "Building frontend assets for production..."
npm run build

# Verify build succeeded
if [ -d "public/build" ]; then
    echo "Frontend build completed successfully!"
else
    echo "Frontend build may have failed. Check for errors above."
    
    # List key directories to help troubleshoot
    echo "Directory structure:"
    ls -la
    echo "Resources directory:"
    ls -la resources || echo "Resources directory not found"
    echo "Public directory:"
    ls -la public || echo "Public directory not found"
fi

# Keep container running for debugging if needed
if [ "$DEBUG" = "true" ]; then
    echo "Debug mode enabled. Container will keep running."
    tail -f /dev/null
fi 