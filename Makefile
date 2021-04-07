install:
	composer install

update:
	composer update

install-build-tools:
	composer install
	sudo npm install --global babel-cli

build:
	babel ./js/app.js -o ./js/compiled.js
	php ./build.php
	rm ./js/compiled.js



