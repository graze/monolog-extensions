.PHONY: all install tests

# Default task
all: install

# Install dependencies
install:
	@composer install

# Run test suites
tests: tests-unit tests-integration

# Run the integration tests
tests-integration:
	@./vendor/bin/phpunit --testsuite integration

# Run the unit tests
tests-unit:
	@./vendor/bin/phpunit --testsuite unit

# Run the unit tests
tests-unit-coverage:
	@./vendor/bin/phpunit --testsuite unit --coverage-text --coverage-html ./tests/report
