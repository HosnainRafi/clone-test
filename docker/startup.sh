#!/bin/bash

# Start PHP-FPM in the background
php-fpm &

# Navigate to the project directory
cd /var/www

# Install npm dependencies if needed
npm install

# Start the npm development server
npm run dev

# Keep the script running
wait
