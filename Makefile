SHELL := /bin/bash

.PHONY: alias push start stop

alias:
	alias s="./symfony"
	alias sc="./symfony console"
	alias sr="./symfony composer"
	alias r="./composer"
	alias pushing='f(){ git add . && git commit -m "$@" && git push;  unset -f f; }; f'

db:
	apt-get update && apt-get install -y sqlite3

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

##########
# SERVER #
##########
start:
	./symfony server:start --port=88 -d

stop:
	./symfony server:stop

###########
#   BIN   #
###########
deployer-bin:
	curl -LO https://deployer.org/releases/v6.8.0/deployer.phar
	mv deployer.phar /usr/local/bin/dep
	chmod +x /usr/local/bin/dep
	dep -V -f -

kint-bin:
	curl -LO https://raw.githubusercontent.com/kint-php/kint/master/build/kint.phar

symfony-cli:
	wget https://get.symfony.com/cli/installer -O - | bash
	mv /root/.symfony/bin/symfony ./symfony
	alias s="./symfony"

composer-cli:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	mv ./composer.phar composer && chmod +x ./composer