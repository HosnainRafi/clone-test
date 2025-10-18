# Docker Production Setup

Complete Docker-based production deployment for MBSTU Website (Laravel + Vue/Inertia).

## 📋 Architecture

```
┌─────────────┐
│   Nginx     │ :80, :443 (Web Server)
└──────┬──────┘
       │
┌──────▼──────┐
│  PHP-FPM    │ :9000 (Application)
└──────┬──────┘
       │
    ┌──▼──────────────┬──────────────┐
    │                 │              │
┌───▼────┐      ┌────▼─────┐   ┌───▼────┐
│ Queue  │      │PostgreSQL│   │ Redis  │
│ Worker │      │   :5432  │   │ :6379  │
└────────┘      └──────────┘   └────────┘
```

## 🐳 Services

### 1. **Nginx** (Web Server)
- **Port**: 80 (HTTP), 443 (HTTPS)
- **Purpose**: Reverse proxy, static file serving
- **Config**: `docker/nginx/`

### 2. **PHP-FPM** (Application)
- **Port**: 9000 (internal)
- **Purpose**: Laravel application
- **PHP Version**: 8.2
- **Extensions**: pdo_pgsql, redis, gd, zip, opcache

### 3. **Queue Worker**
- **Purpose**: Process background jobs
- **Command**: `php artisan queue:work`

### 4. **Scheduler**
- **Purpose**: Run scheduled tasks
- **Command**: `php artisan schedule:run`

### 5. **PostgreSQL**
- **Port**: 5432
- **Purpose**: Primary database
- **Version**: 16-alpine

### 6. **Redis**
- **Port**: 6379
- **Purpose**: Cache & session storage
- **Version**: 7-alpine

## 📁 File Structure

```
codes/
├── docker/
│   ├── nginx/
│   │   ├── nginx.conf           # Main Nginx config
│   │   └── conf.d/
│   │       └── default.conf     # Site configuration
│   └── php/
│       ├── Dockerfile           # Multi-stage PHP build
│       └── php.ini              # PHP configuration
├── docker-compose.prod.yml      # Production compose file
├── .env.prod.example            # Production env template
├── DEPLOYMENT.md                # Detailed deployment guide
├── PRODUCTION-QUICKSTART.md     # Quick reference
└── Makefile                     # Deployment commands
```

## 🚀 Quick Start

### Prerequisites
- Docker Engine 20.10+
- Docker Compose 2.0+
- Make (optional, for easier commands)

### Deploy

```bash
# 1. Setup environment
cp .env.prod.example .env
# Edit .env with your configuration

# 2. Build and start
make build && make up

# 3. Initialize
make migrate
make optimize
```

## 🔧 Configuration Files

### docker-compose.prod.yml
Main orchestration file defining all services, networks, and volumes.

### docker/php/Dockerfile
Multi-stage build:
1. **node-builder**: Builds frontend assets with Vite
2. **production**: PHP-FPM with optimized Laravel setup

### docker/nginx/nginx.conf
- Gzip compression
- Security headers
- Performance tuning

### docker/nginx/conf.d/default.conf
- Laravel routing
- Static file caching
- PHP-FPM proxy

### docker/php/php.ini
- OPcache enabled
- Production error handling
- Performance optimization

## 🔐 Environment Variables

Key variables in `.env`:

```bash
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_HOST=db
DB_PORT=5432
DB_DATABASE=mbstu_db2
DB_USERNAME=mbstu_user
DB_PASSWORD=your_secure_password

# Cache & Sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
```

## 📊 Monitoring

### View Logs
```bash
make logs              # All services
make logs SERVICE=app  # Specific service
```

### Health Check
```bash
make health           # Check all services
make ps              # Container status
make stats           # Resource usage
```

### Access Shells
```bash
make shell           # App container
make shell-db        # PostgreSQL
make shell-redis     # Redis CLI
```

## 💾 Backup & Restore

### Database
```bash
# Backup
make backup-db

# Restore
make restore-db FILE=backups/db_backup_20250107_120000.sql
```

### Storage
```bash
# Backup
make backup-storage

# Restore
tar -xzf backups/storage_backup_20250107_120000.tar.gz
```

## 🔄 Updates & Deployment

### Update Application
```bash
make deploy
```

This will:
1. Pull latest code
2. Rebuild images
3. Restart services
4. Run migrations
5. Clear and rebuild caches

### Manual Update
```bash
git pull origin main
make build
make down
make up
make migrate
make optimize
```

## 🛡️ Security

### SSL/HTTPS Setup

1. **Install Certbot**:
```bash
sudo apt-get install certbot python3-certbot-nginx
```

2. **Obtain Certificate**:
```bash
sudo certbot --nginx -d yourdomain.com
```

3. **Update Nginx Config**:
Add SSL configuration to `docker/nginx/conf.d/default.conf`

4. **Mount Certificates**:
```yaml
nginx:
  volumes:
    - /etc/letsencrypt:/etc/letsencrypt:ro
```

### Security Checklist
- [ ] `APP_DEBUG=false`
- [ ] Strong database password
- [ ] SSL/HTTPS enabled
- [ ] Firewall configured (80, 443 only)
- [ ] Redis password set
- [ ] Regular security updates
- [ ] Regular backups

## ⚡ Performance Optimization

### Enabled Optimizations
1. **OPcache** - PHP bytecode caching
2. **Redis** - Fast cache & sessions
3. **Nginx Gzip** - Compressed responses
4. **Asset Minification** - Vite production build
5. **Laravel Caching** - Config, routes, views cached

### Additional Tuning
```bash
# Database indexing
make shell-db
# Add indexes as needed

# Queue optimization
# Adjust worker count in docker-compose.prod.yml

# PHP-FPM tuning
# Adjust pm.max_children in php-fpm.conf
```

## 🐛 Troubleshooting

### Common Issues

**Permission Errors**
```bash
make permissions
```

**Database Connection Failed**
```bash
docker-compose -f docker-compose.prod.yml ps db
# Check .env DB credentials
```

**Assets Not Loading**
```bash
make build
make up
```

**Queue Not Processing**
```bash
make logs SERVICE=queue
docker-compose -f docker-compose.prod.yml restart queue
```

**High Memory Usage**
```bash
make stats
# Adjust PHP memory_limit in docker/php/php.ini
```

### Debug Mode

To enable debug temporarily:
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Check configuration
```

## 📈 Scaling

### Horizontal Scaling

Scale queue workers:
```bash
docker-compose -f docker-compose.prod.yml up -d --scale queue=3
```

### Load Balancing

For multiple app instances, use Nginx upstream:
```nginx
upstream app_servers {
    server app1:9000;
    server app2:9000;
    server app3:9000;
}
```

## 🔗 CI/CD

GitHub Actions workflow included at `.github/workflows/deploy-production.yml`

### Required Secrets
- `SSH_PRIVATE_KEY` - SSH key for server access
- `SERVER_HOST` - Production server IP/domain
- `SERVER_USER` - SSH username
- `DEPLOY_PATH` - Application path on server

## 📚 Additional Resources

- [DEPLOYMENT.md](DEPLOYMENT.md) - Detailed deployment guide
- [PRODUCTION-QUICKSTART.md](PRODUCTION-QUICKSTART.md) - Quick reference
- [Laravel Deployment Docs](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

## 🆘 Support

For issues or questions:
1. Check logs: `make logs`
2. Review troubleshooting section
3. Contact development team

---

**Built with ❤️ for MBSTU Website**
