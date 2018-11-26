#!/usr/bin/env bash

if [ ! -f box.phar ]; then
    wget https://github.com/humbug/box/releases/download/3.3.1/box.phar -O box.phar
    chmod 0777 box.phar
fi