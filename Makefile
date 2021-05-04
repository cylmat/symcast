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