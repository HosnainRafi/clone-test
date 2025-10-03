#!/bin/bash

# Exit script if any command fails
set -e

echo "🚀 Setting up Laravel + Vue.js project with PostgreSQL..."

# Create directory for Laravel project if it doesn't exist
echo "🔧 Creating Laravel project structure..."
mkdir -p laravel-vue
cd laravel-vue

# Create Laravel project locally first
echo "🔧 Creating Laravel project locally..."
if [ ! -f "composer.json" ]; then
  # Check if composer is installed
  if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed locally. Installing temporary composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --quiet
    php -r "unlink('composer-setup.php');"
    COMPOSER_CMD="php composer.phar"
  else
    COMPOSER_CMD="composer"
  fi

  # Create Laravel project locally
  $COMPOSER_CMD create-project --prefer-dist laravel/laravel .
  
  echo "✅ Laravel project created locally successfully!"
else
  echo "⏩ Laravel project already exists locally, skipping creation..."
fi

# Return to the root directory
cd ..

# Build and start Docker containers
echo "🐳 Building Docker containers..."
docker-compose build
docker-compose up -d

# Configure database connection in .env
echo "🗃️ Configuring PostgreSQL database connection..."
# Update .env file locally
if [ -f "laravel-vue/.env" ]; then
  sed -i '' 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' laravel-vue/.env
  sed -i '' 's/DB_HOST=127.0.0.1/DB_HOST=db/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' laravel-vue/.env
  sed -i '' 's/DB_PORT=3306/DB_PORT=5432/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_PORT=3306/DB_PORT=5432/g' laravel-vue/.env
  sed -i '' 's/DB_DATABASE=laravel/DB_DATABASE=mbstu_db/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_DATABASE=laravel/DB_DATABASE=mbstu_db/g' laravel-vue/.env
  sed -i '' 's/DB_USERNAME=root/DB_USERNAME=mbstu_user/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_USERNAME=root/DB_USERNAME=mbstu_user/g' laravel-vue/.env
  sed -i '' 's/DB_PASSWORD=/DB_PASSWORD=mbstu_password/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_PASSWORD=/DB_PASSWORD=mbstu_password/g' laravel-vue/.env
else
  echo "❌ .env file not found, copying from .env.example..."
  cp laravel-vue/.env.example laravel-vue/.env
  sed -i '' 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' laravel-vue/.env
  sed -i '' 's/DB_HOST=127.0.0.1/DB_HOST=db/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' laravel-vue/.env
  sed -i '' 's/DB_PORT=3306/DB_PORT=5432/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_PORT=3306/DB_PORT=5432/g' laravel-vue/.env
  sed -i '' 's/DB_DATABASE=laravel/DB_DATABASE=mbstu_db/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_DATABASE=laravel/DB_DATABASE=mbstu_db/g' laravel-vue/.env
  sed -i '' 's/DB_USERNAME=root/DB_USERNAME=mbstu_user/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_USERNAME=root/DB_USERNAME=mbstu_user/g' laravel-vue/.env
  sed -i '' 's/DB_PASSWORD=/DB_PASSWORD=mbstu_password/g' laravel-vue/.env 2>/dev/null || sed -i 's/DB_PASSWORD=/DB_PASSWORD=mbstu_password/g' laravel-vue/.env
fi

# Set correct permissions
echo "📂 Setting correct permissions..."
chmod -R 777 laravel-vue/storage laravel-vue/bootstrap/cache 2>/dev/null || echo "Warning: Couldn't set permissions locally, will try in container"
docker-compose exec app chmod -R 777 storage bootstrap/cache

# Install Vue.js and other frontend dependencies
echo "⚙️ Installing Vue.js and frontend dependencies..."

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Install Laravel UI and set up Vue.js with authentication
docker-compose exec app composer require laravel/ui
docker-compose exec app php artisan ui vue --auth

# Install npm dependencies
docker-compose exec app npm install
docker-compose exec app npm install vue-loader@^16.2.0 --save-dev

# Run migrations
echo "🔄 Running database migrations..."
docker-compose exec app php artisan migrate

# Build frontend assets
echo "🎨 Building frontend assets..."
docker-compose exec app npm run dev

echo "✅ Setup complete! Your Laravel + Vue.js application with PostgreSQL is ready."
echo "🌐 Access your application at http://localhost:8000"
echo "📊 Access Adminer database dashboard at http://localhost:8080"
echo ""
echo "💻 You can now develop locally with real-time updates!"
echo "   The project files are available in the 'laravel-vue' directory."
echo "   Any changes you make will be immediately reflected in the running container."
echo ""
echo "📝 For local development without Docker:"
echo "   cd laravel-vue"
echo "   php artisan serve"
