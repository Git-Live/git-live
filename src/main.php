<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


if (!defined('GIT_LIVE_INSTALL_PATH')) {
    define('GIT_LIVE_INSTALL_PATH', __FILE__);
}


try {
    $GitLive = \App::make(\GitLive\GitLive::class);
    $GitLive->execute();
} catch (\Exception $e) {
    $GitLive->ncecho($e->getMessage() . "\n");
}

