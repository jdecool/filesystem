.PHONY: help
help: ## Display this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: all
all: cs stan test

.PHONY: cs
cs: ## Check code style
	vendor/bin/php-cs-fixer fix -v --dry-run

.PHONY: stan
stan: ## Run static analysis
	vendor/bin/phpstan analyse

.PHONY: test
test: ## Run tests
	vendor/bin/phpunit
