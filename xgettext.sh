#!/usr/bin/env bash
find ./src/|grep .php|xargs xgettext --from-code=UTF-8 --keyword=__ -o ./resources/lang/git-live.pot
cp ./resources/lang/git-live.pot ./resources/lang/en_US/LC_MESSAGES/messages.po
