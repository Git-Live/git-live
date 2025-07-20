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

include __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (!defined('GIT_LIVE_INSTALL_PATH')) {
    if (GIT_LIVE_VERSION === 'phar') {
        define('GIT_LIVE_INSTALL_PATH', mb_ereg_replace('^phar://', '', __DIR__));
    } else {
        define('GIT_LIVE_INSTALL_PATH', __DIR__.'/bin/git-live.phar');
    }
}


$GitLive = \GitLive\Application\Facade::make(\GitLive\GitLive::class);
$GitLive->execute();

