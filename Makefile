SAIL := ./vendor/bin/sail

.PHONY: help install precommit dev refresh phpstan pint test eslint

help:
	@echo ""
	@echo "install    - Fresh install"
	@echo ""
	@echo "dev        - Start development setup (vite)"
	@echo "precommit  - Run all pre-commit checks (linting, type checking, tests)"
	@echo "refresh    - Refresh the database"
	@echo ""
	@echo "phpstan    - Run PHPStan"
	@echo "pint       - Run Laravel Pint"
	@echo "test       - Run Pest PHP"
	@echo ""
	@echo "eslint     - Run ESLint"
	@echo ""

install:
	cp --update=none src/.env.example .env || true
	cp --update=none src/.env.example src/.env || true
	$(SAIL) up -d
	$(SAIL) composer install
	$(SAIL) npm ci
	$(SAIL) artisan key:generate --ansi
	$(SAIL) artisan migrate:fresh --seed --force
	@echo "✅ Führe <make dev> aus, um das Frontend zu starten."
	@echo "✅ Installation abgeschlossen!"
	@echo ""

precommit:
	$(SAIL) pint
	$(SAIL) bin phpstan analyse --memory-limit=2G --no-progress --no-interaction
	$(SAIL) pest --type-coverage --compact --min=100
	$(SAIL) test --compact
	$(SAIL) composer audit --format=plain --no-interaction
	$(SAIL) npm audit --audit-level=high
	$(SAIL) npm run lint

dev:
	$(SAIL) npm run dev

eslint:
	$(SAIL) npm run lint

refresh:
	$(SAIL) artisan migrate:fresh --seed

phpstan:
	$(SAIL) bin phpstan analyse --memory-limit=2G --no-progress --no-interaction

pint:
	$(SAIL) pint

test:
	$(SAIL) test --compact