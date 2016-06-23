<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

$is_debug = true;

if (!defined('GIT_LIVE_INSTALL_DIR')) {
    define('GIT_LIVE_INSTALL_DIR', __FILE__);
}

if (!defined('GIT_LIVE_VERSION')) {
    define('GIT_LIVE_VERSION', 'cli');
}
if (!class_exists('\GitLive\Autoloader', false)) {
    include 'libs/GitLive/Autoloader.php';
}


$Autoloader = new \GitLive\Autoloader;
$Autoloader->register();


if (GIT_LIVE_VERSION === 'phar') {
    $Autoloader->addNamespace('GitLive', 'phar://git-live.phar/libs/GitLive');
} else {
    $Autoloader->addNamespace('GitLive', __DIR__.'/libs/GitLive');
}

// LANG
$lang = trim(`echo \$LANG`);
if (empty($lang)) {
    $lang = 'ja_JP.UTF-8';
}


try {
    if (DIRECTORY_SEPARATOR === '\\') {
        mb_internal_encoding('utf8');
        mb_http_output('sjis-win');
        mb_http_input('sjis-win');
    }
    $GitLive = new GitLive\GitLive;
    $GitLive->execute();
} catch (exception $e) {
    $this->ncecho($e->getMessage()."\n");
}
