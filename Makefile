COLOR_GREEN = \033[0;32m
COLOR_YELLOW = \033[1;33m
COLOR_END = \033[0m

SAIL := ./vendor/bin/sail

# Functions for colored output
define HEADER
	@echo -e "$(COLOR_YELLOW)$(1)$(COLOR_END)"
endef

define COMMAND
	@echo -e "  $(COLOR_GREEN)$(1)$(COLOR_END)$(2)"
endef

.PHONY: install precommit dev refresh phpstan pint test eslint

all:
	@echo ""
	$(call HEADER,Usage: )
	@echo "  make COMMAND"
	@echo ""
	$(call HEADER,Development commands:)
	$(call COMMAND,install,    - Fresh install)
	$(call COMMAND,dev,        - Start development setup (vite))
	$(call COMMAND,precommit,  - Run all pre-commit checks (linting$(COLOR_END), type checking$(COLOR_END), tests))
	$(call COMMAND,refresh,    - Refresh the database)
	@echo ""
	$(call HEADER,Backend commands:)
	$(call COMMAND,phpstan,    - Run PHPStan)
	$(call COMMAND,pint,       - Run Laravel Pint)
	$(call COMMAND,test,       - Run Pest PHP)
	@echo ""
	$(call HEADER,Frontend commands:)
	$(call COMMAND,eslint,     - Run ESLint)
	@echo ""

install:
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