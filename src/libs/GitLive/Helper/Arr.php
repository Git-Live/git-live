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

namespace GitLive\Helper;

use ArrayAccess;
use GitLive\GitBase;
use GitLive\Support\Collection;
use InvalidArgumentException;

/**
 * Class Arr
 *
 * @category   GitCommand
 * @package    GitLive\Support
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 * @since      2018-12-16
 */
class Arr extends GitBase
{
    /**
     * Determine whether the given value is array accessible.
     *
     * @param mixed $value
     * @return bool
     */
    public static function accessible($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param array|\ArrayAccess $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function add($array, string $key, $value): array
    {
        if (static::get($array, $key) === null) {
            static::set($array, $key, $value);
        }

        return $array;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array|\ArrayAccess $array
     * @return array
     */
    public static function collapse($array): array
    {
        $results = [];
        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $results = array_merge($results, $values);
        }

        return $results;
    }

    /**
     * Cross join the given arrays, returning all possible permutations.
     *
     * @param array ...$arrays
     * @return array
     */
    public static function crossJoin(...$arrays): array
    {
        $results = [[]];
        foreach ($arrays as $index => $array) {
            $append = [];
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;
                    $append[] = $product;
                }
            }
            $results = $append;
        }

        return $results;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array|\ArrayAccess $array
     * @return array
     */
    public static function divide($array): array
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param array|ArrayAccess $array
     * @param string $prepend
     * @return array
     */
    public static function dot($array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param array|\ArrayAccess $array
     * @param array|string $keys
     * @return array
     */
    public static function except($array, $keys): array
    {
        static::forget($array, $keys);

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param array|\ArrayAccess $array
     * @param int|string $key
     * @return bool
     */
    public static function exists($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array|\ArrayAccess $array
     * @param null|callable $callback
     * @param mixed $default
     * @return mixed
     */
    public static function first($array, ?callable $callback = null, $default = null)
    {
        if ($callback === null) {
            if (empty($array)) {
                return value($default);
            }
            /** @noinspection LoopWhichDoesNotLoopInspection */
            foreach ($array as $item) {
                return $item;
            }
        }
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array|\ArrayAccess $array
     * @param null|callable $callback
     * @param mixed $default
     * @return mixed
     */
    public static function last($array, ?callable $callback = null, $default = null)
    {
        if ($callback === null) {
            return empty($array) ? value($default) : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array|\ArrayAccess $array
     * @param float|int $depth
     * @return array
     */
    public static function flatten($array, $depth = INF): array
    {
        $result = [];
        foreach ($array as $item) {
            $item = $item instanceof Collection ? $item->all() : $item;
            if (!is_array($item)) {
                $result[] = $item;
            } elseif ($depth === 1) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, array_values($item));
            } else {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, static::flatten($item, $depth - 1));
            }
        }

        return $result;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param array|string $keys
     * @return void
     */
    public static function forget(&$array, $keys): void
    {
        $original = &$array;
        $keys = (array)$keys;
        if (count($keys) === 0) {
            return;
        }
        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }
            $parts = explode('.', $key);
            // clean up before each pass
            $array = &$original;
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }
            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param null|string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, ?string $key, $default = null)
    {
        if (!static::accessible($array)) {
            return value($default);
        }
        if ($key === null) {
            return $array;
        }
        if (static::exists($array, $key)) {
            return $array[$key];
        }
        if (strpos($key, '.') === false) {
            return $array[$key] ?? value($default);
        }
        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param array|string $keys
     * @return bool
     */
    public static function has($array, $keys): bool
    {
        if ($keys === null) {
            return false;
        }
        $keys = (array)$keys;
        if (!$array) {
            return false;
        }
        if ($keys === []) {
            return false;
        }
        foreach ($keys as $key) {
            $subKeyArray = $array;
            if (static::exists($array, $key)) {
                continue;
            }
            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param array $array |\ArrayAccess $array
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array|\ArrayAccess $array
     * @param array|string $keys
     * @return array
     */
    public static function only($array, $keys): array
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param array|\ArrayAccess $array
     * @param array|string $value
     * @param null|array|string $key
     * @return array
     */
    public static function pluck($array, $value, $key = null): array
    {
        $results = [];
        [$value, $key] = static::explodePluckParameters($value, $key);
        foreach ($array as $item) {
            $itemValue = data_get($item, $value);
            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if ($key === null) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);
                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string)$itemKey;
                }
                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Push an item onto the beginning of an array.
     *
     * @param array|\ArrayAccess $array
     * @param mixed $value
     * @param mixed $key
     * @return array
     */
    public static function prepend($array, $value, $key = null): array
    {
        if ($key === null) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param array|ArrayAccess $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function pull(&$array, string $key, $default = null)
    {
        $value = static::get($array, $key, $default);
        static::forget($array, $key);

        return $value;
    }

    /**
     * Get one or a specified number of random values from an array.
     *
     * @param array|\ArrayAccess $array
     * @param null|float|int $number
     * @throws \InvalidArgumentException
     * @return mixed
     *
     */
    public static function random($array, $number = null)
    {
        $requested = $number ?? 1;
        $count = count($array);
        if ($requested > $count) {
            throw new InvalidArgumentException(
                "You requested " . $requested . " items, but there are only " . $count . " items available."
            );
        }
        if ($number === null) {
            return $array[array_rand($array)];
        }
        if ((int)$number === 0) {
            return [];
        }
        $keys = array_rand($array, $number);
        $results = [];
        foreach ((array)$keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array|ArrayAccess $array
     * @param null|string $key
     * @param mixed $value
     * @return array
     */
    public static function set(&$array, ?string $key, $value): array
    {
        if ($key === null) {
            return $array = $value;
        }

        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Shuffle the given array and return the result.
     *
     * @param array|\ArrayAccess $array
     * @param null|int $seed
     * @throws \Exception
     * @throws \Exception
     * @return array
     */
    public static function shuffle($array, ?int $seed = null): array
    {
        if ($seed === null) {
            shuffle($array);
        } else {
            mt_srand($seed);
            usort($array, static function () {
                /** @noinspection RandomApiMigrationInspection */
                return mt_rand(-1, 1);
            });
        }

        return $array;
    }

    /**
     * Sort the array using the given callback or "dot" notation.
     *
     * @param array|\ArrayAccess $array
     * @param null|callable|string $callback
     * @return array
     */
    public static function sort($array, $callback = null): array
    {
        return Collection::make($array)->sortBy($callback)->all();
    }

    /**
     * Recursively sort an array by keys and values.
     *
     * @param array|\ArrayAccess $array
     * @return array
     */
    public static function sortRecursive($array): array
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value);
            }
        }
        unset($value);
        if (static::isAssoc($array)) {
            ksort($array);
        } else {
            sort($array);
        }

        return $array;
    }

    /**
     * Convert the array into a query string.
     *
     * @param array|\ArrayAccess $array
     * @return string
     */
    public static function query($array): string
    {
        return http_build_query((array)$array, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * Filter the array using the given callback.
     *
     * @param array|\ArrayAccess $array
     * @param callable $callback
     * @return array
     */
    public static function where($array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param mixed $value
     * @return array
     */
    public static function wrap($value): array
    {
        if ($value === null) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param array|string $value
     * @param null|array|string $key
     * @return array
     */
    protected static function explodePluckParameters($value, $key): array
    {
        $value = is_string($value) ? explode('.', $value) : $value;
        $key = $key === null || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }
}
