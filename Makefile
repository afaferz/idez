SHELL := bash
PATH  := bin:${PATH}
PWD   := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

.PHONY: migrate migrate_down migrate_up migrate_version docker prod docker_delve local swaggo test

# ==============================================================================
# Go migrate postgresql

force:
	migrate -database postgres://postgres:postgres@localhost:5432/auth_db?sslmode=disable -path migrations force 1

version:
	migrate -database postgres://postgres:postgres@localhost:5432/auth_db?sslmode=disable -path migrations version

migrate_up:
	migrate -database postgres://postgres:postgres@localhost:5432/auth_db?sslmode=disable -path migrations up 1

migrate_down:
	migrate -database postgres://postgres:postgres@localhost:5432/auth_db?sslmode=disable -path migrations down 1


# ==============================================================================
# Docker compose commands
up:
	echo "Starting local environment"
	echo "Starting container"
	docker compose -f docker-compose.yml build && docker compose -f docker-compose.yml up -d
down:
	docker stop $(FILES)
	docker rm $(FILES)

install:
	./compose.local.sh
# ==============================================================================

# ==============================================================================
# Main

run:
	./vendor/bin/sail up

test:
	./vendor/bin/sail test

ifeq (require,$(firstword $(MAKECMDGOALS)))
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # ...and turn them into do-nothing targets
  $(eval $(RUN_ARGS):;@:)
endif

.PHONY: run
require:
	./vendor/bin/sail compose require $(RUN_ARGS)

# ==============================================================================
# Modules support

deps-reset:
	git checkout -- go.mod
	go mod tidy
	go mod vendor

tidy:
	go mod tidy
	go mod vendor

deps-upgrade:
	# go get $(go list -f '{{if not (or .Main .Indirect)}}{{.Path}}{{end}}' -m all)
	go get -u -t -d -v ./...
	go mod tidy
	go mod vendor

deps-cleancache:
	go clean -modcache


# ==============================================================================
# Docker support

FILES := $(shell docker ps -aq)


clean:
	docker system prune -f

logs-local:
	docker logs -f $(FILES)