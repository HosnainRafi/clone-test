# MBSTU Website 2025

## Tech Stack

- **Backend**: Laravel 9.x
- **Frontend**: Vue.js 3.x
- **Database**: PostgreSQL
- **Environment**: Docker

## Prerequisites

### For Docker Setup

- Docker and Docker Compose
- Git

### For Local Setup (Without Docker)

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- PostgreSQL (or any database of your choice)
- Git

## Setup Instructions

### Quick Start with Docker

1. Clone the repository:

   ```bash
   git clone <repository_url>
   cd mbstu-website-2025
   ```

2. Run the setup script:

   ```bash
   ./setup.sh
   ```

3. Access the application at [http://localhost:8000](http://localhost:8000)

### Quick Start without Docker

1. Clone the repository:

   ```bash
   git clone <repository_url>
   cd mbstu-website-2025/laravel-vue
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Create and configure the environment file:

   ```bash
   cp .env.example .env
   # Edit .env file to set your database configuration
   # DB_CONNECTION=pgsql
   # DB_HOST=127.0.0.1
   # DB_PORT=5432
   # DB_DATABASE=mbstu_db
   # DB_USERNAME=your_db_user
   # DB_PASSWORD=your_db_password
   ```

4. Generate application key:

   ```bash
   php artisan key:generate
   ```

5. Run database migrations:

   ```bash
   php artisan migrate
   ```

6. Install and build frontend assets:

   ```bash
   npm install
   npm run dev
   ```

7. Start the local development server:

   ```bash
   php artisan serve
   ```

8. Access the application at [http://localhost:8000](http://localhost:8000)

### Manual Docker Setup

1. Start the Docker containers:

   ```bash
   docker-compose build
   docker-compose up -d
   ```

2. Install Laravel dependencies:

   ```bash
   docker-compose exec app composer install
   ```

3. Configure the environment:

   ```bash
   docker-compose exec app cp .env.example .env
   docker-compose exec app php artisan key:generate
   ```

4. Set up the database:

   ```bash
   docker-compose exec app php artisan migrate
   ```

5. Install and build frontend assets:

   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run dev
   ```

## Docker Services

- **app**: PHP 8.2 service running Laravel
- **webserver**: Nginx web server
- **db**: PostgreSQL database
- **adminer**: Database management dashboard

## Development

### Local Development with Bind Mounts

The project is set up with bind mounts for real-time development. This means:

- All project files are available in your local directory
- Changes made locally are immediately reflected in the Docker container
- No need to rebuild containers or restart services when changing code
- Hot reload is enabled for Vue.js components

#### Workflow

1. Edit files directly on your local machine using your preferred editor/IDE
2. The changes are automatically synced to the container via bind mount
3. For Vue.js files, changes are hot-reloaded in the browser
4. For PHP/Laravel files, just refresh the browser to see changes

### Running Commands

To run Laravel commands:

```bash
docker-compose exec app php artisan <command>
```

To run Composer commands:

```bash
docker-compose exec app composer <command>
```

To run NPM commands:

```bash
docker-compose exec app npm <command>
```

## Project Structure

- `/docker` - Docker configuration files
- `/docker/nginx/conf.d` - Nginx configuration
- `/docker/php` - PHP configuration
- `/laravel-vue` - Main Laravel and Vue.js project directory
  - `/app` - Laravel application files
  - `/resources` - Laravel resources (views, JavaScript, CSS)
  - `/resources/js/components` - Vue.js components
  - `/routes` - Laravel routes definitions
  - `/public` - Publicly accessible files

## Database

- **Host**: db
- **Port**: 5432
- **Name**: mbstu_db
- **Username**: mbstu_user
- **Password**: mbstu_password

### Database Management

This project includes Adminer, a lightweight database management tool with a web interface.

- **Access URL**: [http://localhost:8080](http://localhost:8080)
- **Login credentials**:
  - System: PostgreSQL
  - Server: db
  - Username: mbstu_user
  - Password: mbstu_password
  - Database: mbstu_db
