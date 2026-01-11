#!/bin/sh
set -e

# Create directories if they don't exist
mkdir -p /app/public/img/uploads
mkdir -p /app/public/img/profiles

# Set permissions to allow PHP-FPM (running as www-data) to write
chmod -R 777 /app/public/img/uploads
chmod -R 777 /app/public/img/profiles

# Execute the main command
exec "$@"
