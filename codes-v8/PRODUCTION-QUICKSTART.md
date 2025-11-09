# Production Quick Start Guide

Quick reference for deploying the MBSTU Website to production.

## 🚀 First Time Deployment

```bash
# 1. Clone and navigate
git clone <repository-url>
cd mbstu-website-2025/codes

# 2. Setup environment
cp .env.prod.example .env
nano .env  # Configure APP_KEY, DB_PASSWORD, APP_URL, etc.

# 3. Build and start
make build
make up

# 4. Initialize database
make migrate
make seed  # Optional

# 5. Optimize
make optimize
```

## 📦 Using Make Commands

```bash
make help              # Show all available commands
make build             # Build Docker images
make up                # Start all services
make down              # Stop all services
make restart           # Restart services
make logs              # View all logs
make logs SERVICE=app  # View specific service logs
make shell             # Access app container
make migrate           # Run migrations
make optimize          # Cache config/routes/views
make clear-cache       # Clear all caches
make backup-db         # Backup database
make deploy            # Full deployment (pull, build, migrate, optimize)
make health            # Check service health
```

## 🔧 Manual Commands (without Make)

```bash
# Build
docker-compose -f docker-compose.prod.yml build

# Start
docker-compose -f docker-compose.prod.yml up -d

# Migrate
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Optimize
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Logs
docker-compose -f docker-compose.prod.yml logs -f

# Stop
docker-compose -f docker-compose.prod.yml down
```

## 🔄 Updating Application

```bash
# Simple update
make deploy

# Or manually:
git pull origin main
make build
make down
make up
sleep 10
make migrate
make clear-cache
make optimize
```

## 🔍 Health Check

```bash
# Check all services
make health

# Check specific service
docker-compose -f docker-compose.prod.yml ps
```

## 📊 Monitoring

```bash
# View logs
make logs

# View specific service logs
make logs SERVICE=nginx
make logs SERVICE=app
make logs SERVICE=queue

# Resource usage
make stats
```

## 💾 Backup

```bash
# Backup database
make backup-db

# Backup storage
make backup-storage

# Restore database
make restore-db FILE=backups/db_backup_20250107_120000.sql
```

## 🐛 Troubleshooting

```bash
# Fix permissions
make permissions

# Clear all caches
make clear-cache

# View Laravel logs
make logs SERVICE=app

# Access app shell
make shell

# Access database
make shell-db

# Access Redis
make shell-redis
```

## 🔐 Security Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Use strong `DB_PASSWORD`
- [ ] Configure `APP_URL` correctly
- [ ] Set up SSL/HTTPS (see DEPLOYMENT.md)
- [ ] Configure firewall (allow only 80, 443)
- [ ] Set up regular backups

## 📚 Full Documentation

For detailed information, see [DEPLOYMENT.md](DEPLOYMENT.md)

## 🆘 Common Issues

### Permission Errors
```bash
make permissions
```

### Database Connection Failed
```bash
# Check database is running
docker-compose -f docker-compose.prod.yml ps db

# Check credentials in .env match docker-compose.prod.yml
```

### Assets Not Loading
```bash
# Rebuild with fresh assets
make build
make up
```

### Queue Not Processing
```bash
# Check queue worker
docker-compose -f docker-compose.prod.yml logs queue

# Restart queue worker
docker-compose -f docker-compose.prod.yml restart queue
```

## 📞 Support

For issues or questions, contact the development team.
