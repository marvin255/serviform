#!/usr/bin/make

SHELL = /bin/sh

php_container_name := php
docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
docker_compose_yml := docker/docker-compose.yml
user_id := $(shell id -u)

.PHONY : help build shell test fixer linter
.DEFAULT_GOAL := build

# --- [ Development tasks ] --------------------------------------------------------------------------------------------

build: ## Build container and install composer libs
	$(docker_compose_bin) --file "$(docker_compose_yml)" build
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" composer install

shell: ## Runs shell in container
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" /bin/bash

test: ## Execute tests
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpunit --configuration phpunit.xml.dist --coverage-html=tests/coverage

fixer: ## Run fixes for code style
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/php-cs-fixer fix -v

linter: ## Execute code checks
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run --stop-on-violation
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpcpd ./src -v
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpunit --configuration phpunit.xml.dist
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpmetrics src
