.PHONY: deploy install soft-deploy server credential test

OPTIONS?=

test: install credential
	vendor/bin/phpunit --color --testdox tests

install:
	composer install -o

deploy: credential
	gcloud app deploy --project='feeder-230308' --promote --stop-previous-version $(OPTIONS)

soft-deploy: credential
	gcloud app deploy --project='feeder-230308' --no-promote --no-stop-previous-version $(OPTIONS)

server: install credential
	php -S localhost:8080 -t public/

##############################

credential: credential/plurk.php

credential/plurk.php:
	echo "噗浪功能需要的 credential 檔案 $@ 不存在!" && false
