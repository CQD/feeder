.PHONY: deploy installNoDev installWithDev deploy soft-deploy server post-deploy

OPTIONS?=

installNoDev:
	-composer install -o --no-dev

installWithDev:
	-composer install -o

deploy: installNoDev credential/plurk.php
	gcloud app deploy -v 'prod' --project='feeder-230308' --promote --stop-previous-version $(OPTIONS)
	@$(MAKE) post-deploy

soft-deploy: installNoDev
	gcloud app deploy -v 'prod' --project='feeder-230308' --no-promote --no-stop-previous-version $(OPTIONS)
	@$(MAKE) post-deploy

post-deploy:
	@echo "\033[1;33mDeploy done.\033[m"

server: installWithDev
	php -S localhost:8080 -t public/

credential/plurk.php:
	echo "噗浪功能需要的 credential 檔案 $@ 不存在!" && false
