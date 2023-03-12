.PHONY: install
install: install_vendors install_assets

.PHONY: install_vendors
install_vendors:
	symfony composer install

.PHONY: install_assets
install_assets:
	symfony php bin/console assets:install --symlink public
	npm run build

.PHONY: test
test:
	./vendor/bin/pest

.PHONY: fresh
fresh:
	symfony php bin/console doctrine:database:drop --force --if-exists
	symfony php bin/console doctrine:database:create
	symfony php bin/console doctrine:migrations:migrate --no-interaction
	symfony php bin/console doctrine:fixtures:load --no-interaction

.PHONY: start
start:
	docker compose up -d
	symfony proxy:domain:attach funix-cars
	symfony proxy:start
	symfony server:start -d

.PHONY: stop
stop:
	docker compose down
	symfony server:stop
