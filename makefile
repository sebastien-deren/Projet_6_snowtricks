#--SYMFONY--#
SYMFONY = symfony
SYMFONY_CONSOLE = $(SYMFONY) console
SYMFONY_SERVER_START = $(SYMFONY) serve -d
SYMFONY_SERVER_STOP = $(SYMFONY) server:stop
SYMFONY_PROJECT_OPEN = $(SYMFONY) open:local

#--DOCKER--#
DOCKER =docker
DOCKER_COMPOSE = $(DOCKER) compose
DOCKER_COMPOSE_START = $(DOCKER_COMPOSE) up -d
DOCKER_COMPOSE_STOP = $(DOCKER_COMPOSE) down

#--DOCTRINE--#
DOCTRINE= $(SYMFONY_CONSOLE) doctrine
DOCTRINE_MAKE_MIGRATION = $(SYMFONY_CONSOLE) make:migration
DOCTRINE_MIGRATE= $(DOCTRINE):migration:migrate
DOCTRINE_MAKE_DB=$(DOCTRINE):database:create --if-not-exists

#--COMPOSER--#
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install


docker-up:
	$(DOCKER_COMPOSE_START)

docker-down:
	$(DOCKER_COMPOSE_STOP)
.PHONY:docker-down

sf-start:
	$(SYMFONY_SERVER_START)
.PHONY: launch

sf-stop:
	$(SYMFONY_SERVER_STOP)
.PHONY:sf-stop

sf-open:
	$(SYMFONY_PROJECT_OPEN)
.PHONY:sf-open

composer-install:
	$(COMPOSER_INSTALL)
.PHONY:composer-install

composer-update:
	$(COMPOSER) update
.PHONY:composer-update

dmakedb:
	$(DOCTRINE_MAKE_DB)
.PHONY:dmakedb

dmm:
	$(DOCTRINE_MIGRATION_MIGRATE)
.PHONY: dmm



start: sf-start docker-up sf-open
.PHONY:start

stop: sf-stop docker-down
.PHONY: stop

install: docker-up composer-install dmakedb dmm sf-start sf-open
.PHONY: install