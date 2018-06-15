SHELL = /bin/sh

DOCKER ?= $(shell which docker)
PHP_VER := 7.2
IMAGE := graze/php-alpine:${PHP_VER}-test
VOLUME := /srv
DOCKER_RUN_BASE := ${DOCKER} run --rm -t -v $$(pwd):${VOLUME} -w ${VOLUME}
DOCKER_RUN := ${DOCKER_RUN_BASE} ${IMAGE}

PREFER_LOWEST ?=

.PHONY: build build-update ensure-composer-file
.PHONY: test test-unit test-integration

# Default task
all: build

# Building

build: ## Install the dependencies
build: ensure-composer-file
	make 'composer-install --optimize-autoloader --prefer-dist ${PREFER_LOWEST}'

build-update: ## Update the dependencies
build-update: ensure-composer-file
	make 'composer-update --optimize-autoloader --prefer-dist ${PREFER_LOWEST}'

ensure-composer-file: # Update the composer file
	make 'composer-config platform.php ${PHP_VER}'

composer-%: ## Run a composer command, `make "composer-<command> [...]"`.
	${DOCKER} run -t --rm \
        -v $$(pwd):/app:delegated \
        -v ~/.composer:/tmp:delegated \
        -v ~/.ssh:/root/.ssh:ro \
        composer --ansi --no-interaction $* $(filter-out $@,$(MAKECMDGOALS))


# Testing

test: ## Run the unit testsuites.
test: test-unit test-integration

test-unit: ## Run the unit testsuite.
	${DOCKER_RUN} vendor/bin/phpunit --colors=always --testsuite unit

test-integration: ## Run the unit testsuite.
	${DOCKER_RUN} vendor/bin/phpunit --colors=always --testsuite integration


# Help

help: ## Show this help message.
	echo "usage: make [target] ..."
	echo ""
	echo "targets:"
	egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'
