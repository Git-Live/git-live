#!/usr/bin/env bash

if [ ! -f box4.phar ]; then
    wget https://github.com/box-project/box/releases/download/4.6.6/box.phar -O box4.phar
    chmod 0777 box.phar
fi

if [ ! -f box.phar ]; then
    wget https://github.com/box-project/box/releases/download/3.16.0/box.phar -O box.phar
    chmod 0777 box.phar
fi

if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/download/2.8.10/composer.phar -O composer.phar
    chmod 0777 composer.phar
fi

