FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    libxml2-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# --- START OF CHANGES ---

# Install Node.js v20 (CHANGED FROM v16)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy startup script
COPY --chown=root:root ./docker/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Copy only the Laravel project directory
COPY --chown=www:www ./laravel-vue /var/www

# --- NEW PERMISSIONS FIX ---
# Change ownership of the project directory to the 'www' user
# This ensures npm has permission to create node_modules
RUN chown -R www:www /var/www

# Switch to the 'www' user
USER www

# --- END OF CHANGES ---

# Install npm dependencies (this will now run as 'www' user with correct permissions)
WORKDIR /var/www
RUN npm install

# Expose ports for PHP-FPM and Node.js development server
EXPOSE 9000 5173

# Run startup script that starts both PHP-FPM and npm dev server
CMD ["/usr/local/bin/startup.sh"]

