start:
	php artisan serve --host 0.0.0.0

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku

lint:
	composer exec --verbose phpcs -- --standard=PSR12 app public resources

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 app public resources

install:
	composer install

