# Production Deployment Guide

This guide explains how to deploy the MBSTU Website to production using Docker Compose.

## Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- Domain name configured (optional for HTTPS)
- SSL certificates (optional for HTTPS)

## Initial Setup

### 1. Clone Repository

```bash
git clone <repository-url>
cd mbstu-website-2025/codes
```

### 2. Configure Environment

```bash
# Copy production environment template
cp .env.prod.example .env

# Edit environment variables
nano .env
```

**Important variables to configure:**

- `APP_KEY` - Generate with: `php artisan key:generate --show`
- `APP_URL` - Your production domain
- `DB_PASSWORD` - Strong database password
- `REDIS_PASSWORD` - Strong Redis password (optional)
- Mail configuration (SMTP settings)

### 3. Build and Start Services

```bash
# Build Docker images
docker-compose -f docker-compose.prod.yml build

# Start services in detached mode
docker-compose -f docker-compose.prod.yml up -d
```

### 4. Run Database Migrations

```bash
# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# (Optional) Seed database
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
```

### 5. Optimize Application

```bash
# Cache configuration
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache

# Cache routes
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache

# Cache views
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## Service Management

### View Logs

```bash
# All services
docker-compose -f docker-compose.prod.yml logs -f

# Specific service
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f nginx
docker-compose -f docker-compose.prod.yml logs -f queue
```

### Restart Services

```bash
# Restart all services
docker-compose -f docker-compose.prod.yml restart

# Restart specific service
docker-compose -f docker-compose.prod.yml restart app
```

### Stop Services

```bash
# Stop all services
docker-compose -f docker-compose.prod.yml down

# Stop and remove volumes (WARNING: This deletes data!)
docker-compose -f docker-compose.prod.yml down -v
```

## SSL/HTTPS Configuration

### Using Let's Encrypt (Recommended)

1. Install Certbot:

```bash
sudo apt-get update
sudo apt-get install certbot python3-certbot-nginx
```

2. Obtain SSL certificate:

```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

3. Update nginx configuration in `docker/nginx/conf.d/default.conf`:

```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # ... rest of configuration
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

4. Mount SSL certificates in `docker-compose.prod.yml`:

```yaml
nginx:
  volumes:
    - /etc/letsencrypt:/etc/letsencrypt:ro
```

## Updating Application

### Deploy New Version

```bash
# Pull latest code
git pull origin main

# Rebuild images
docker-compose -f docker-compose.prod.yml build

# Stop services
docker-compose -f docker-compose.prod.yml down

# Start with new images
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear and rebuild cache
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## Backup and Restore

### Database Backup

```bash
# Create backup
docker-compose -f docker-compose.prod.yml exec db pg_dump -U mbstu_user mbstu_db2 > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
docker-compose -f docker-compose.prod.yml exec -T db psql -U mbstu_user mbstu_db2 < backup_20250107_120000.sql
```

### Storage Backup

```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/

# Restore storage
tar -xzf storage_backup_20250107_120000.tar.gz
```

## Monitoring

### Health Checks

```bash
# Check service status
docker-compose -f docker-compose.prod.yml ps

# Check database connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker --execute="DB::connection()->getPdo();"

# Check Redis connection
docker-compose -f docker-compose.prod.yml exec redis redis-cli ping
```

### Performance Monitoring

```bash
# View resource usage
docker stats

# Check queue status
docker-compose -f docker-compose.prod.yml exec app php artisan queue:monitor
```

## Troubleshooting

### Permission Issues

```bash
# Fix storage permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 storage bootstrap/cache
```

### Clear All Caches

```bash
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec app php artisan view:clear
```

### View Application Logs

```bash
# Laravel logs
docker-compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log

# Nginx access logs
docker-compose -f docker-compose.prod.yml exec nginx tail -f /var/log/nginx/access.log

# Nginx error logs
docker-compose -f docker-compose.prod.yml exec nginx tail -f /var/log/nginx/error.log
```

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong passwords for database and Redis
- [ ] Configure SSL/HTTPS
- [ ] Set up firewall rules (allow only 80, 443)
- [ ] Regular security updates
- [ ] Enable Redis password authentication
- [ ] Restrict database access to app container only
- [ ] Regular backups
- [ ] Monitor logs for suspicious activity

## Production Optimization

1. **OPcache** - Already enabled in `php.ini`
2. **Redis** - Used for cache and sessions
3. **Asset Optimization** - Built assets are minified
4. **Nginx Gzip** - Enabled for static assets
5. **Database Indexing** - Ensure proper indexes on tables
6. **Queue Workers** - Process jobs asynchronously

## Support

For issues or questions, please contact the development team.
