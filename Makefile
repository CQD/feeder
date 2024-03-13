.PHONY: deploy installNoDev installWithDev deploy soft-deploy server credential test

OPTIONS?=

installNoDev: credential
	-composer install -o --no-dev

installWithDev: credential
	-composer install -o

deploy: installNoDev credential
	gcloud app deploy --project='feeder-230308' --promote --stop-previous-version $(OPTIONS)

soft-deploy: installNoDev
	gcloud app deploy --project='feeder-230308' --no-promote --no-stop-previous-version $(OPTIONS)

server: installWithDev
	php -S localhost:8080 -t public/

test: installWithDev
	vendor/bin/phpunit --color --testdox tests

##############################

credential: credential/plurk.php

credential/plurk.php:
	echo "噗浪功能需要的 credential 檔案 $@ 不存在!" && false
