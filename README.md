# Git Live Flow
<!--
[![travis-ci](https://travis-ci.org/Git-Live/git-live.svg?branch=master)](https://travis-ci.org/)
-->
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/Git-Live/git-live/master/LICENSE)
![php-version](https://img.shields.io/badge/php-5.3-blue.svg)
![php-version](https://img.shields.io/badge/php-5.4-blue.svg)
![php-version](https://img.shields.io/badge/php-5.5-blue.svg)
![php-version](https://img.shields.io/badge/php-5.6-blue.svg)
![php-version](https://img.shields.io/badge/php-7.0-blue.svg)
![php-version](https://img.shields.io/badge/php-hhvm-blue.svg)
## git-flow

[git-flow](http://nvie.com/posts/a-successful-git-branching-model/)
は[github-flow](http://scottchacon.com/2011/08/31/github-flow.html)で指摘されているような複雑さはあるものの、
すでに標準的なワークフローとして馴染んでいます。

それと同時に、ソースコードを管理するシステムとしてGitHubやそれに類するホスティングサービスを採用するプロジェクトもまた多くなっています。
GitHubを採用する恩恵は非常に大きなものですが、その最たるものはプルリクエストです。
[github-flow](http://scottchacon.com/2011/08/31/github-flow.html)では、

> 「GitHub has an amazing code review system called Pull Requests that I fear not enough people know about.」
> (GitHubには、残念だが十分な人々には知られていない プルリクエスト と呼ばれる素晴らしいコードレビューの仕組みがある。)

と言われていますが、2016年現在、GitHubを使っていてプルリクエストを知らない人はいないのではないでしょうか。

git-flowはGit Hubを用いない開発手法であるため、残念ながら、プルリクエストの仕組みがgit-flowには含まれません。

git-live-flowはその問題点を解決するべく、git-flowにプルリクエストの機構を組み込みました。


## 要件
git-liveは、phpで作られた、git コマンドの集合体です。

git liveフローを実施するために必要なコマンドが集まっています。

git-liveを使用するためには、PHP7.0以上が必要です。

## インストール方法

### Unix系OS

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ wget https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar -O git-live
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
もしくは
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ curl https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar -o git-live
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

その後、
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ chmod 0777 ./git-live
$ sudo mv ./git-live /usr/local/bin/git-live

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

### Windows

以下のファイルを、パスが通ったディレクトリにおいてください。

 * https://raw.githubusercontent.com/Git-Live/git-live/v2.0/git-live.php
 * https://raw.githubusercontent.com/Git-Live/git-live/v2.0/bin/git-live.bat
