# Git Live Flow
<!--
[![travis-ci](https://travis-ci.org/Git-Live/git-live.svg?branch=master)](https://travis-ci.org/)
-->
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/Git-Live/git-live/master/LICENSE)
![php-version](https://img.shields.io/badge/php-7.1-blue.svg)
![php-version](https://img.shields.io/badge/php-7.2-blue.svg)
![php-version](https://img.shields.io/badge/php-7.3-blue.svg)
![php-version](https://img.shields.io/badge/php-7.4-blue.svg)
![php-version](https://img.shields.io/badge/php-8.0-blue.svg)
![php-version](https://img.shields.io/badge/php-8.1-blue.svg)
## git-flow
Although "[git-flow](http://nvie.com/posts/a-successful-git-branching-model/)" has the complexity as pointed out in "[github-flow](http://scottchacon.com/2011/08/31/github-flow.html)", it is familiar already as a standard workflow

At the same time, there are also many projects that adopt GitHub and similar hosting services as source code management system.
The benefit of adopting GitHub is very big, but the best one is pull request.

It is said to be 


> GitHub has an amazing code review system called Pull Requests that I fear not enough people know about.

in  [github-flow](http://scottchacon.com/2011/08/31/github-flow.html), but as of 2016, there is no one who does not know the pull request when using GitHub.

Unfortunately, since git-flow is a development method that does not use Git Hub, the mechanism of pull request is not included in git-flow.

In order to solve the problem, git-live-flow has incorporated a pull request mechanism into git-flow.

## System requirements
git-live is a collection of git commands created with php.

git live The commands necessary to implement the flow are gathered.

In order to use git-live, PHP 7.0 or higher is necessary.

## Installation method

### Unix/Linux/MacOS

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ wget https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar -O git-live
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

OR

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ curl https://raw.githubusercontent.com/Git-Live/git-live/master/bin/git-live.phar -o git-live
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

after that,

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ chmod 0777 ./git-live
$ sudo mv ./git-live /usr/local/bin/git-live

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

### Windows
Please place the following files in the directory where the path passed.

 * https://raw.githubusercontent.com/Git-Live/git-live/v3.0/git-live.php
 * https://raw.githubusercontent.com/Git-Live/git-live/v3.0/bin/git-live.bat
