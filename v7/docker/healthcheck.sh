#!/bin/sh
# Health check script for production deployment

set -e

COMPOSE_FILE="docker-compose.prod.yml"
COMPOSE="docker-compose -f $COMPOSE_FILE"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "🏥 MBSTU Website - Production Health Check"
echo "=========================================="
echo ""

# Function to check service status
check_service() {
    SERVICE=$1
    if $COMPOSE ps $SERVICE | grep -q "Up"; then
        echo "${GREEN}✓${NC} $SERVICE is running"
        return 0
    else
        echo "${RED}✗${NC} $SERVICE is not running"
        return 1
    fi
}

# Function to check port
check_port() {
    HOST=$1
    PORT=$2
    SERVICE=$3
    if nc -z $HOST $PORT 2>/dev/null; then
        echo "${GREEN}✓${NC} $SERVICE port $PORT is accessible"
        return 0
    else
        echo "${RED}✗${NC} $SERVICE port $PORT is not accessible"
        return 1
    fi
}

FAILED=0

# Check Docker services
echo "📦 Checking Docker Services..."
echo "----------------------------"
check_service "nginx" || FAILED=$((FAILED + 1))
check_service "app" || FAILED=$((FAILED + 1))
check_service "queue" || FAILED=$((FAILED + 1))
check_service "scheduler" || FAILED=$((FAILED + 1))
check_service "db" || FAILED=$((FAILED + 1))
check_service "redis" || FAILED=$((FAILED + 1))
echo ""

# Check ports
echo "🔌 Checking Network Ports..."
echo "----------------------------"
check_port "localhost" "80" "Nginx HTTP" || FAILED=$((FAILED + 1))
check_port "localhost" "5432" "PostgreSQL" || FAILED=$((FAILED + 1))
check_port "localhost" "6379" "Redis" || FAILED=$((FAILED + 1))
echo ""

# Check database connection
echo "🗄️  Checking Database Connection..."
echo "-----------------------------------"
if $COMPOSE exec -T app php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
    echo "${GREEN}✓${NC} Database connection successful"
else
    echo "${RED}✗${NC} Database connection failed"
    FAILED=$((FAILED + 1))
fi
echo ""

# Check Redis connection
echo "💾 Checking Redis Connection..."
echo "-------------------------------"
if $COMPOSE exec -T redis redis-cli ping > /dev/null 2>&1; then
    echo "${GREEN}✓${NC} Redis connection successful"
else
    echo "${RED}✗${NC} Redis connection failed"
    FAILED=$((FAILED + 1))
fi
echo ""

# Check disk space
echo "💿 Checking Disk Space..."
echo "------------------------"
DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -lt 80 ]; then
    echo "${GREEN}✓${NC} Disk usage: ${DISK_USAGE}%"
elif [ $DISK_USAGE -lt 90 ]; then
    echo "${YELLOW}⚠${NC} Disk usage: ${DISK_USAGE}% (Warning)"
else
    echo "${RED}✗${NC} Disk usage: ${DISK_USAGE}% (Critical)"
    FAILED=$((FAILED + 1))
fi
echo ""

# Check container resource usage
echo "📊 Container Resource Usage..."
echo "-----------------------------"
docker stats --no-stream --format "table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}" | grep mbstu || true
echo ""

# Check application logs for errors
echo "📝 Recent Application Errors..."
echo "------------------------------"
ERROR_COUNT=$($COMPOSE exec -T app sh -c "tail -n 100 storage/logs/laravel.log 2>/dev/null | grep -c ERROR || echo 0")
if [ $ERROR_COUNT -eq 0 ]; then
    echo "${GREEN}✓${NC} No recent errors in application logs"
else
    echo "${YELLOW}⚠${NC} Found $ERROR_COUNT errors in recent logs"
    echo "Run 'make logs SERVICE=app' to view details"
fi
echo ""

# Summary
echo "=========================================="
if [ $FAILED -eq 0 ]; then
    echo "${GREEN}✓ All health checks passed!${NC}"
    exit 0
else
    echo "${RED}✗ $FAILED health check(s) failed!${NC}"
    echo ""
    echo "Troubleshooting steps:"
    echo "1. Check logs: make logs"
    echo "2. Restart services: make restart"
    echo "3. Check configuration: cat .env"
    exit 1
fi
