#Makefile

gendiff:
	bin/gendiff -h

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 bin src 
install:
	composer install
