#!/usr/bin/env bash
set -euf -o pipefail
export PHP_PATH='php'

./download-box.sh

$PHP_PATH composer.phar install

function restorePlatform {
    $PHP_PATH composer.phar config --unset platform
    mv -f composer.lock.back composer.lock || true
}

if [ -f ./bin/git-live.phar ]; then
    rm ./bin/git-live.phar
fi

if [ -f ./resources/lang/en_US/LC_MESSAGES/messages.mo ]; then
    rm ./resources/lang/en_US/LC_MESSAGES/messages.mo
fi

if [ -f ./resources/lang/ja_JP/LC_MESSAGES/messages.mo ]; then
    rm ./resources/lang/ja_JP/LC_MESSAGES/messages.mo
fi

msgfmt  -o  ./resources/lang/en_US/LC_MESSAGES/messages.mo ./resources/lang/en_US/LC_MESSAGES/messages.po
msgfmt  -o  ./resources/lang/ja_JP/LC_MESSAGES/messages.mo ./resources/lang/ja_JP/LC_MESSAGES/messages.po

$PHP_PATH composer.phar config platform.php 7.2.5

cp composer.lock composer.lock.back || true
mv -f vendor vendor.back || true

$PHP_PATH composer.phar install --no-dev
$PHP_PATH composer.phar dump-autoload -a

$PHP_PATH box.phar compile -vvv

rm -rf vendor
mv -f vendor.back vendor || true

zip -r windows.zip ./bin
restorePlatform

$PHP_PATH bin/git-live.phar
