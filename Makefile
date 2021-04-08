install:
	composer install

update:
	composer update

install-build-tools:
	echo "Ce n'est pas utilise pour le dev"
	composer install
	sudo npm install --global babel-cli

build:
	echo "Ce n'est pas utilise pour le dev"
	babel ./js/app.js -o ./js/compiled.js
	php ./build.php
	rm ./js/compiled.js



