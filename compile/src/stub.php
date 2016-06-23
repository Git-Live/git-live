#!/usr/bin/env php
<?php
define('GIT_LIVE_INSTALL_DIR', __FILE__);
define('GIT_LIVE_VERSION', 'phar');
Phar::mapPhar( 'git-live.phar' );

include 'phar://git-live.phar/libs/GitLive/Autoloader.php';
include 'phar://git-live.phar/main.php';

__HALT_COMPILER(); ?>