<?php

/**
 * This file is part of Git-Live
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id\$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 */

include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (!defined('GIT_LIVE_INSTALL_PATH')) {
    define('GIT_LIVE_INSTALL_PATH', dirname(__DIR__));
}

try {
    $GitLive = \App::make(\GitLive\GitLive::class);
    $GitLive->execute();
} catch (\Exception $e) {
    $GitLive->ncecho($e->getMessage() . "\n");
}
