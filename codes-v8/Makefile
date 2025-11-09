.PHONY: help build up down restart logs shell migrate fresh seed optimize backup

# Docker Compose file
COMPOSE_FILE := docker-compose.prod.yml
COMPOSE := docker-compose -f $(COMPOSE_FILE)

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build Docker images
	$(COMPOSE) build --no-cache

up: ## Start all services
	$(COMPOSE) up -d

down: ## Stop all services
	$(COMPOSE) down

restart: ## Restart all services
	$(COMPOSE) restart

logs: ## View logs (use: make logs SERVICE=app)
	@if [ -z "$(SERVICE)" ]; then \
		$(COMPOSE) logs -f; \
	else \
		$(COMPOSE) logs -f $(SERVICE); \
	fi

ps: ## Show running containers
	$(COMPOSE) ps

shell: ## Access app container shell
	$(COMPOSE) exec app sh

shell-db: ## Access database shell
	$(COMPOSE) exec db psql -U mbstu_user mbstu_db2

shell-redis: ## Access Redis CLI
	$(COMPOSE) exec redis redis-cli

migrate: ## Run database migrations
	$(COMPOSE) exec app php artisan migrate --force

migrate-rollback: ## Rollback last migration
	$(COMPOSE) exec app php artisan migrate:rollback --force

fresh: ## Fresh database with migrations
	$(COMPOSE) exec app php artisan migrate:fresh --force

seed: ## Seed database
	$(COMPOSE) exec app php artisan db:seed --force

optimize: ## Optimize application (cache config, routes, views)
	$(COMPOSE) exec app php artisan config:cache
	$(COMPOSE) exec app php artisan route:cache
	$(COMPOSE) exec app php artisan view:cache

clear-cache: ## Clear all caches
	$(COMPOSE) exec app php artisan optimize:clear
	$(COMPOSE) exec app php artisan cache:clear
	$(COMPOSE) exec app php artisan config:clear
	$(COMPOSE) exec app php artisan route:clear
	$(COMPOSE) exec app php artisan view:clear

permissions: ## Fix storage permissions
	$(COMPOSE) exec app chown -R www-data:www-data storage bootstrap/cache
	$(COMPOSE) exec app chmod -R 775 storage bootstrap/cache

backup-db: ## Backup database
	@mkdir -p backups
	$(COMPOSE) exec db pg_dump -U mbstu_user mbstu_db2 > backups/db_backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "Database backup created in backups/"

restore-db: ## Restore database (use: make restore-db FILE=backups/db_backup_20250107_120000.sql)
	@if [ -z "$(FILE)" ]; then \
		echo "Error: Please specify FILE=path/to/backup.sql"; \
		exit 1; \
	fi
	$(COMPOSE) exec -T db psql -U mbstu_user mbstu_db2 < $(FILE)

backup-storage: ## Backup storage directory
	@mkdir -p backups
	tar -czf backups/storage_backup_$$(date +%Y%m%d_%H%M%S).tar.gz storage/
	@echo "Storage backup created in backups/"

deploy: ## Deploy new version (pull, build, migrate, optimize)
	git pull origin main
	$(COMPOSE) build
	$(COMPOSE) down
	$(COMPOSE) up -d
	sleep 10
	$(COMPOSE) exec app php artisan migrate --force
	$(COMPOSE) exec app php artisan optimize:clear
	$(COMPOSE) exec app php artisan config:cache
	$(COMPOSE) exec app php artisan route:cache
	$(COMPOSE) exec app php artisan view:cache
	@echo "Deployment completed!"

health: ## Check service health
	@echo "=== Service Status ==="
	$(COMPOSE) ps
	@echo "\n=== Database Connection ==="
	@$(COMPOSE) exec app php artisan tinker --execute="echo 'DB: ' . (DB::connection()->getPdo() ? 'Connected' : 'Failed') . PHP_EOL;" 2>/dev/null || echo "Failed"
	@echo "\n=== Redis Connection ==="
	@$(COMPOSE) exec redis redis-cli ping 2>/dev/null || echo "Failed"

stats: ## Show container resource usage
	docker stats --no-stream

clean: ## Remove stopped containers and unused images
	docker system prune -f

clean-all: ## Remove all containers, images, and volumes (WARNING: Destructive!)
	@echo "WARNING: This will remove all containers, images, and volumes!"
	@read -p "Are you sure? [y/N] " -n 1 -r; \
	echo; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		$(COMPOSE) down -v; \
		docker system prune -af --volumes; \
	fi
