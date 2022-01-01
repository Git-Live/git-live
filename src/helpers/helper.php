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

use GitLive\Application\Facade;
use GitLive\Helper\Arr;
use GitLive\Helper\Resource;
use GitLive\Support\Collection;
use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('__')) {
    function __(string $message): string
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
        die(1);
    }
}

if (!function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('collect')) {
    function collect($value): Collection
    {
        return new Collection($value);
    }
}

if (!function_exists('resource')) {
    /**
     * @param null|string $file_name
     * @throws \ErrorException
     * @return \GitLive\Helper\Resource|string
     */
    function resource(?string $file_name = null)
    {
        $obj = Facade::make(Resource::class);
        if ($file_name === null) {
            return $obj;
        }

        return $obj->get($file_name);
    }
}

if (!function_exists('data_get')) {
    /**
     * @param      array|Collection $target
     * @param array|string $key
     * @param null $default
     * @return array|mixed
     */
    function data_get($target, $key, $default = null)
    {
        if ($key === null) {
            return $target;
        }
        $key = is_array($key) ? $key : explode('.', $key);
        while (($segment = array_shift($key)) !== null) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (!is_array($target)) {
                    return value($default);
                }
                $result = [];
                foreach ($target as $item) {
                    $result[] = data_get($item, $key);
                }

                return in_array('*', $key, true) ? Arr::collapse($result) : $result;
            }
            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }
}
