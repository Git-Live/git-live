#!/usr/bin/env bash

if [ ! -f box.phar ]; then
    wget https://github.com/box-project/box/releases/download/3.14.0/box.phar -O box.phar
    chmod 0777 box.phar
fi

if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/download/2.8.10/composer.phar -O composer.phar
    chmod 0777 composer.phar
fi

