SHELL := /bin/bash

########
# DIST #
########
dist:
	git init
	git config user.name "cylmat"
	git config user.email "cyrilmatte.pro@gmail.com"
	git remote add origin https://github.com/cylmat/symfony5
	git cb base
	git pull origin base
	git b --set-upstream-to=origin/base
	git cb book
	git pull origin book
	git b --set-upstream-to=origin/book
	composer install
	echo 'y' | sc doctrine:migrations:migrate
	echo 'y' | sc doctrine:fixtures:load

##############
# GIT REBASE #
##############
rebase:
	git rebase main -s recursive -X theirs

###########
#   BIN   #
###########
tests:
	php bin/console doctrine:fixtures:load -n
	php bin/phpunit
.PHONY: tests

clear:
	rm -rf var/cache/*
	rm -rf var/logs/*
	chmod -R 777 var/*

deployer-bin:
	curl -LO https://deployer.org/releases/v6.8.0/deployer.phar
	mv deployer.phar /usr/local/bin/dep
	chmod +x /usr/local/bin/dep
	dep -V -f -

kint-bin:
	curl -LO https://raw.githubusercontent.com/kint-php/kint/master/build/kint.phar

symfony-cli:
	wget https://get.symfony.com/cli/installer -O - | bash
	mv /root/.symfony/bin/symfony /usr/local/bin/symfony

clean-redis:
	php bin/console redis:flushall --client=session -n
	php bin/console redis:flushall --client=cache -n

warmup:
	php bin/console cache:warmup

test-static:
	bin/phpstan analyse

test-cover:
	XDEBUG_MODE=coverage vendor/bin/phpunit -c ./ src/ --coverage-html=reports/coverage

install:
	php bin/composer install

install-hooks:
	@echo "Removing hooks (pre-commit, commit-msg)..." ; rm -f .git/hooks/pre-commit ; rm -f .git/hooks/commit-msg
	@echo "Installing hooks (pre-commit, commit-msg)..." ; cp bin/pre-commit .git/hooks/pre-commit ; cp bin/commit-msg .git/hooks/commit-msg
