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
	git push heroku

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app resources

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 app resources

install:
	composer install

restart:
	heroku restart -a webpage-analyzer
