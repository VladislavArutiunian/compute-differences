install:
	composer install

console:
	composer exec --verbose psysh

lint:
	composer exec phpcs -- --standard=PSR12 src/ tests/

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
