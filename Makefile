SHELL := bash
PATH  := bin:${PATH}
PWD   := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
PROJECT_NAME := idez

# ==============================================================================
# Docker compose commands
up:
	echo "Starting container"
	docker compose build --no-cache && docker compose up --force-recreate -d
key:
	docker compose exec app php artisan key:generate
attach:
	docker exec -it api_app /bin/bash
down:
	docker compose down
# ==============================================================================

# ==============================================================================
# Main
phpunit: ## Runs PhpUnit tests
	./vendor/bin/phpunit -c phpunit.xml --coverage-html reports/coverage --coverage-clover reports/clover.xml --log-junit reports/junit.xml
# run:
# 	./vendor/bin/sail up
# ifeq (require,$(firstword $(MAKECMDGOALS)))
#   RUN_ARGS_REQUIRE := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
#   $(eval $(RUN_ARGS_REQUIRE):;@:)
# endif

# .PHONY: require
# require:
# 	./vendor/bin/sail compose require $(RUN_ARGS_REQUIRE)

# ifeq (sail,$(firstword $(MAKECMDGOALS)))
#   RUN_ARGS_SAIL := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
#   $(eval $(RUN_ARGS_SAIL):;@:)
# endif

# .PHONY: sail
# sail:
# 	./vendor/bin/sail $(RUN_ARGS_SAIL)

# ==============================================================================
# Docker support

FILES := $(shell docker ps -aq)

.PHONY: clean
clean:
	docker ps -a | awk '/$(PROJECT_NAME)/ { print $$1 }' | xargs docker rm -f
clean-im:
	docker images -a | awk '/$(PROJECT_NAME)/ { print $$3 }' | xargs docker rmi -f
prune:
	docker system prune -a
logs-local:
	docker logs -f $(FILES)