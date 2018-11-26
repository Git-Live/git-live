<?php

namespace GitLive\Application;

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
     * @param       $concrete
     * @param array $with
     * @return mixed
     * @throws \ReflectionException
     */
    public static function make($concrete, $with = [])
    {
        $Container = new Container();

        $Container->setWith($with);

        return $Container->build($concrete);
    }

}