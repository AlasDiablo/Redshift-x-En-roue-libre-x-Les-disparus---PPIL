install:
	composer install

update:
	composer update

install-build-tools:
	composer install
	sudo npm install --global babel-cli

build:
	babel ./js/app.js -o ./js/compiled.js
	wget --post-data="input=`cat ./js/compiled.js`" --output-document=./js/app.min.js https://javascript-minifier.com/raw
	rm ./js/compiled.js



