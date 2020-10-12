#!/usr/bin/make

php_container_name := php
docker_compose_yml := docker/docker-compose.yml
user_id := $(shell id -u)
docker_compose := $(shell command -v docker-compose 2> /dev/null)  --file "$(docker_compose_yml)"
docker_php := $(docker_compose) run --rm -u "$(user_id)" "$(php_container_name)"

.PHONY : help build shell test coverage fixer linter
.DEFAULT_GOAL := build

# --- [ Development tasks ] --------------------------------------------------------------------------------------------

build: ## Build container and install composer libs
	$(docker_compose) build
	$(docker_php) composer install

shell: ## Runs shell in container
	$(docker_php) /bin/bash

test: ## Execute tests
	$(docker_php) vendor/bin/phpunit --configuration phpunit.xml.dist

coverage: ## Execute tests with coverage
	$(docker_php) vendor/bin/phpunit --configuration phpunit.xml.dist --coverage-html=tests/coverage

fixer: ## Run fixes for code style
	$(docker_php) vendor/bin/php-cs-fixer fix -v

linter: ## Execute code checks
	$(docker_php) vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run --stop-on-violation
	$(docker_php) vendor/bin/phpcpd ./src -v
	$(docker_php) vendor/bin/psalm --show-info=true
