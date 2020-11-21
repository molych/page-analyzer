start:
	php artisan serve --host 0.0.0.0

migrate:
	php artisan migrate

heroku migrate:
	heroku run php artisan migrate

console:
	php artisan tinker

log:
	heroku logs --tail

route:
	php artisan route:list

test:
	php artisan test

deploy:
	git push heroku main

lint:
	composer phpcs 

lint-fix:
	composer phpcbf 

install:
	composer install

restart:
	heroku restart -a webpage-analyzer

test-coverage:
	composer phpunit tests -- --coverage-clover build/logs/clover.xml