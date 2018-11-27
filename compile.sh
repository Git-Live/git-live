#!/usr/bin/env bash
set -euf -o pipefail

./download-box.sh

php composer.phar install

function restorePlatform {
    php composer.phar config --unset platform
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

php composer.phar config platform.php 7.1.0

cp composer.lock composer.lock.back || true
mv -f vendor vendor.back || true

php composer.phar install --no-dev

php box.phar compile -vv

rm -rf vendor
mv -f vendor.back vendor || true

restorePlatform
