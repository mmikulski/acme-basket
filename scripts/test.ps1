docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -w /app php:8.0-cli php ./vendor/bin/phpunit
