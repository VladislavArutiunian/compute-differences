install:
	composer install

console:
	composer exec --verbose psysh

lint:
	composer exec --verbose ./vendor/bin/phpcs -- --standard=PSR12 src

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
