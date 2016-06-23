#!/usr/bin/env php
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveCompile
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
define('GIT_LIVE_INSTALL_DIR', __FILE__);
define('GIT_LIVE_VERSION', 'phar');
Phar::mapPhar( 'git-live.phar' );

include 'phar://git-live.phar/libs/GitLive/Autoloader.php';
include 'phar://git-live.phar/main.php';

__HALT_COMPILER(); ?>