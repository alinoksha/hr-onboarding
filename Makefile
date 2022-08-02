RUN_IN_NGINX := docker exec nginx

RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

ifeq (artisan,$(firstword $(MAKECMDGOALS)))
  $(eval $(RUN_ARGS):;@:)
endif

ifeq (composer,$(firstword $(MAKECMDGOALS)))
  $(eval $(RUN_ARGS):;@:)
endif

test:
	$(RUN_IN_NGINX) php vendor/bin/phpunit --stop-on-failure ./tests/
up:
	docker compose up -d
php:
	docker exec -it nginx bash
composer:
	$(RUN_IN_NGINX) composer $(RUN_ARGS)
down:
	docker compose down
restart: down up
