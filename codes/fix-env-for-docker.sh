#!/bin/sh
# Script to update .env file for Docker production environment

echo "Updating .env file for Docker environment..."

# Backup existing .env
if [ -f .env ]; then
    cp .env .env.backup
    echo "✓ Backed up .env to .env.backup"
fi

# Update database host and port for Docker
sed -i.tmp 's/^DB_HOST=.*/DB_HOST=db/' .env
sed -i.tmp 's/^DB_PORT=.*/DB_PORT=5432/' .env

# Update Redis host and port for Docker
sed -i.tmp 's/^REDIS_HOST=.*/REDIS_HOST=redis/' .env
sed -i.tmp 's/^REDIS_PORT=.*/REDIS_PORT=6379/' .env
sed -i.tmp 's/^REDIS_PASSWORD=.*/REDIS_PASSWORD=/' .env

# Update cache, queue, and session drivers to use Redis
sed -i.tmp 's/^CACHE_STORE=.*/CACHE_STORE=redis/' .env
sed -i.tmp 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/' .env
sed -i.tmp 's/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/' .env

# Clean up temp files
rm -f .env.tmp

echo "✓ Updated .env file for Docker"
echo ""
echo "Changes made:"
echo "  - DB_HOST=db"
echo "  - DB_PORT=5432"
echo "  - REDIS_HOST=redis"
echo "  - REDIS_PORT=6379"
echo "  - REDIS_PASSWORD= (empty)"
echo "  - CACHE_STORE=redis"
echo "  - QUEUE_CONNECTION=redis"
echo "  - SESSION_DRIVER=redis"
echo ""
echo "Please verify your .env file and restart containers:"
echo "  docker-compose -f docker-compose.prod.yml down"
echo "  docker-compose -f docker-compose.prod.yml up -d"
