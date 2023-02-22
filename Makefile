## —— Misc 🛠️️ —————————————————————————————————————————————————————————————————
.DEFAULT_GOAL = help
.PHONY: coverage # complete if needed

## —— Global 🛠️️ —————————————————————————————————————————————————————————————————
help: ## Commands list
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Executables 🛠️️ —————————————————————————————————————————————————————————————————
DOCKER_COMPOSE = docker-compose -f docker-compose.yml
DOCKER_EXEC = docker exec -ti
SERVER = test-slim-server
EXEC = @$(DOCKER_EXEC) $(SERVER) /bin/bash -c

## —— Docker 🐳  ———————————————————————————————————————————————————————————————
build: ## Build dev docker images
	@$(DOCKER_COMPOSE) build

build_no_cache: ## Build dev docker images without cache
	@$(DOCKER_COMPOSE) build --no-cache

up: ## Run project containers
	@$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Stop project containers
	@$(DOCKER_COMPOSE) stop

bash: up ## Run bash in server container
	@$(DOCKER_EXEC) $(SERVER) /bin/bash

## —— Symfony 🎶 ———————————————————————————————————————————————————————————————
composer_update: composer.jon ## Run composer update
	@$(EXEC) 'composer update'

composer_install: composer.lock ## run composer install
	@$(EXEC) 'composer install'

install: composer_install ## Alias of composer_install / composer_update

schema_create: ## Create database schema
	@$(EXEC) 'vendor/bin/doctrine orm:schema-tool:create'

schema_update: ## Create database schema
	@$(EXEC) 'vendor/bin/doctrine orm:schema-tool:update'

load_fixtures: ## Load database's fixtures
	@$(EXEC) 'php bin/console load:fixtures'

## —— Tests ✅ ———————————————————————————————————————————————————————————————
phpunit: ## Run phpunit
	@$(EXEC) 'vendor/bin/phpunit -c phpunit.xml.dist'

coverage: ## Run phpunit with code coverage
	@$(EXEC) 'vendor/bin/phpunit -c phpunit.xml.dist --coverage-html coverage'

phpcs_fix: ## Run php-cs-fixer 
	@$(EXEC) 'vendor/bin/php-cs-fixer fix --diff --config=.php-cs-fixer.dist.php'

phpcs: ## Run php-cs-fixer with dry run
	@$(EXEC) 'vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php-cs-fixer.dist.php'

phpstan: ## RUN phpstan
	@$(EXEC) 'vendor/bin/phpstan analyse --xdebug'
