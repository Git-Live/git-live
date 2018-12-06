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

use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('__')) {
    function __($message)
    {
        return _($message);
    }
}

if (!function_exists('dump')) {
    function dump($var)
    {
        VarDumper::dump($var);
        die;
    }
}

if (!function_exists('dd')) {
    function dd($var)
    {
        dump($var);
        die;
    }
}
