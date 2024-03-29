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

namespace GitLive\Application;

use ErrorException;
use ReflectionException;

/**
 * Class Facade
 *
 * @category   GitCommand
 * @package    GitLive\Application
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018/11/24
 */
class Facade
{
    /**
     * @param \Closure|string $concrete
     * @param array $with
     * @throws \ErrorException
     * @return mixed
     */
    public static function make($concrete, array $with = [])
    {
        $Container = new Container();

        $Container->setWith($with);

        $res = null;

        try {
            $res = $Container->build($concrete);
        } catch (ReflectionException $exception) {
        }

        if ($res === null) {
            throw new ErrorException($concrete . 'is undefined concrete.');
        }

        return $res;
    }
}
