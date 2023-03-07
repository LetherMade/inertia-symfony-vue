.PHONY: install
install: install_vendors

.PHONY: install_vendors
install_vendors:
	symfony composer install

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
