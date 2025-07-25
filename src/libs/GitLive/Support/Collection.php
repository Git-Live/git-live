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

namespace GitLive\Support;

use ArrayAccess;
use ArrayIterator;
use CachingIterator;
use Countable;
use Exception;
use GitLive\Helper\Arr;
use IteratorAggregate;
use JsonException;
use JsonSerializable;
use stdClass;
use Symfony\Component\VarDumper\VarDumper;
use Traversable;

/**
 * Class Collection
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
 * @property-read HigherOrderCollectionProxy $average
 * @property-read HigherOrderCollectionProxy $avg
 * @property-read HigherOrderCollectionProxy $contains
 * @property-read HigherOrderCollectionProxy $each
 * @property-read HigherOrderCollectionProxy $every
 * @property-read HigherOrderCollectionProxy $filter
 * @property-read HigherOrderCollectionProxy $first
 * @property-read HigherOrderCollectionProxy $flatMap
 * @property-read HigherOrderCollectionProxy $groupBy
 * @property-read HigherOrderCollectionProxy $keyBy
 * @property-read HigherOrderCollectionProxy $map
 * @property-read HigherOrderCollectionProxy $max
 * @property-read HigherOrderCollectionProxy $min
 * @property-read HigherOrderCollectionProxy $partition
 * @property-read HigherOrderCollectionProxy $reject
 * @property-read HigherOrderCollectionProxy $sortBy
 * @property-read HigherOrderCollectionProxy $sortByDesc
 * @property-read HigherOrderCollectionProxy $sum
 * @property-read HigherOrderCollectionProxy $unique
 */
class Collection implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
{
    /**
     * The items contained in the collection.
     *
     * @var array
     */
    protected array $items = [];
    /**
     * The methods that can be proxied.
     *
     * @var array
     */
    protected static array $proxies = [
        'average', 'avg', 'contains', 'each', 'every', 'filter', 'first',
        'flatMap', 'groupBy', 'keyBy', 'map', 'max', 'min', 'partition',
        'reject', 'some', 'sortBy', 'sortByDesc', 'sum', 'unique',
    ];

    /**
     * Create a new collection.
     *
     * @param mixed $items
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /** @noinspection MagicMethodsValidityInspection */
    /**
     * Dynamically access collection proxies.
     *
     * @param string $key
     * @throws \Exception
     * @return \GitLive\Support\HigherOrderCollectionProxy
     */
    public function __get(string $key)
    {
        /** @noinspection TypeUnsafeArraySearchInspection */
        if (!in_array($key, static::$proxies)) {
            /** @noinspection ThrowRawExceptionInspection */
            throw new Exception("Property [" . $key . "] does not exist on this collection instance.");
        }

        return new HigherOrderCollectionProxy($this, $key);
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param mixed $items
     * @return static
     */
    public static function make($items = []): Collection
    {
        return new static($items);
    }

    /**
     * Wrap the given value in a collection if applicable.
     *
     * @param mixed $value
     * @return static
     */
    public static function wrap($value): Collection
    {
        return $value instanceof self
            ? new static($value)
            : new static(Arr::wrap($value));
    }

    /**
     * Get the underlying items from the given collection if applicable.
     *
     * @param array|static $value
     * @return array|static
     */
    public static function unwrap($value)
    {
        return $value instanceof self ? $value->all() : $value;
    }

    /**
     * Create a new collection by invoking the callback a given amount of times.
     *
     * @param int $number
     * @param null|callable $callback
     * @return static
     */
    public static function times(int $number, ?callable $callback = null): Collection
    {
        if ($number < 1) {
            return new static;
        }
        if ($callback === null) {
            return new static(range(1, $number));
        }

        return (new static(range(1, $number)))->map($callback);
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the average value of a given key.
     *
     * @param null|callable|string $callback
     * @return float|int
     */
    public function avg($callback = null)
    {
        $callback = $this->valueRetriever($callback);
        $items = $this->map(static function ($value) use ($callback) {
            return $callback($value);
        })->filter(static function ($value) {
            return $value !== null;
        });
        if ($count = $items->count()) {
            return $items->sum() / $count;
        }

        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }

    /**
     * Alias for the "avg" method.
     *
     * @param null|callable|string $callback
     * @return float|int
     */
    public function average($callback = null)
    {
        return $this->avg($callback);
    }

    /**
     * Get the median of a given key.
     *
     * @param null|array|string $key
     * @return mixed
     */
    public function median($key = null)
    {
        $values = (isset($key) ? $this->pluck($key) : $this)
            ->filter(static function ($item) {
                return $item !== null;
            })->sort()->values();
        $count = $values->count();
        if ($count === 0) {
            return null;
        }
        $middle = (int)($count / 2);
        if ($count % 2) {
            return $values->get($middle);
        }

        return (new static([
            $values->get($middle - 1), $values->get($middle),
        ]))->average();
    }

    /**
     * Get the mode of a given key.
     *
     * @param null|array|string $key
     * @return null|array
     */
    public function mode($key = null): ?array
    {
        if ($this->count() === 0) {
            return null;
        }
        $collection = isset($key) ? $this->pluck($key) : $this;
        $counts = new self;
        $collection->each(static function ($value) use ($counts) {
            $counts[$value] = isset($counts[$value]) ? $counts[$value] + 1 : 1;
        });
        $sorted = $counts->sort();
        $highestValue = $sorted->last();

        return $sorted->filter(static function ($value) use ($highestValue) {
            /** @noinspection TypeUnsafeComparisonInspection */
            return $value == $highestValue;
        })->sort()->keys()->all();
    }

    /**
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse(): Collection
    {
        return new static(Arr::collapse($this->items));
    }

    /**
     * Alias for the "contains" method.
     *
     * @param mixed $key
     * @param mixed $operator
     * @param mixed $value
     * @return bool
     */
    public function some($key, $operator = null, $value = null): bool
    {
        return $this->contains(...func_get_args());
    }

    /**
     * Determine if an item exists in the collection.
     *
     * @param mixed $key
     * @param mixed $operator
     * @param mixed $value
     * @return bool
     */
    public function contains($key, $operator = null, $value = null): bool
    {
        if (func_num_args() === 1) {
            if ($this->useAsCallable($key)) {
                $placeholder = new stdClass;

                return $this->first($key, $placeholder) !== $placeholder;
            }

            /** @noinspection TypeUnsafeArraySearchInspection */
            return in_array($key, $this->items);
        }

        return $this->contains($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Determine if an item exists in the collection using strict comparison.
     *
     * @param mixed $key
     * @param mixed $value
     * @return bool
     */
    public function containsStrict($key, $value = null): bool
    {
        if (func_num_args() === 2) {
            return $this->contains(static function ($item) use ($key, $value) {
                return data_get($item, $key) === $value;
            });
        }
        if ($this->useAsCallable($key)) {
            return $this->first($key) !== null;
        }

        return in_array($key, $this->items, true);
    }

    /**
     * Cross join with the given lists, returning all possible permutations.
     *
     * @param mixed ...$lists
     * @return static
     */
    public function crossJoin(...$lists): Collection
    {
        return new static(Arr::crossJoin(
            $this->items,
            ...array_map([$this, 'getArrayableItems'], $lists)
        ));
    }

    /**
     * Dump the collection and end the script.
     *
     * @param array $args
     * @return void
     * @codeCoverageIgnore
     */
    public function dd(...$args): void
    {
        call_user_func_array([$this, 'dump'], $args);
        die(1);
    }

    /**
     * Dump the collection.
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function dump(): self
    {
        (new static(func_get_args()))
            ->push($this)
            ->each(static function ($item) {
                VarDumper::dump($item);
            });

        return $this;
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function diff($items): Collection
    {
        return new static(array_diff($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param mixed $items
     * @param callable $callback
     * @return static
     */
    public function diffUsing($items, callable $callback): Collection
    {
        return new static(array_udiff($this->items, $this->getArrayableItems($items), $callback));
    }

    /**
     * Get the items in the collection whose keys and values are not present in the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function diffAssoc($items): Collection
    {
        return new static(array_diff_assoc($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get the items in the collection whose keys and values are not present in the given items.
     *
     * @param mixed $items
     * @param callable $callback
     * @return static
     */
    public function diffAssocUsing($items, callable $callback): Collection
    {
        return new static(array_diff_uassoc($this->items, $this->getArrayableItems($items), $callback));
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function diffKeys($items): Collection
    {
        return new static(array_diff_key($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param mixed $items
     * @param callable $callback
     * @return static
     */
    public function diffKeysUsing($items, callable $callback): Collection
    {
        return new static(array_diff_ukey($this->items, $this->getArrayableItems($items), $callback));
    }

    /**
     * Execute a callback over each item.
     *
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback): self
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Execute a callback over each nested chunk of items.
     *
     * @param callable $callback
     * @return static
     */
    public function eachSpread(callable $callback): Collection
    {
        return $this->each(static function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * Determine if all items in the collection pass the given test.
     *
     * @param callable|string $key
     * @param mixed $operator
     * @param mixed $value
     * @return bool
     */
    public function every($key, $operator = null, $value = null): bool
    {
        if (func_num_args() === 1) {
            $callback = $this->valueRetriever($key);
            foreach ($this->items as $k => $v) {
                if (!$callback($v, $k)) {
                    return false;
                }
            }

            return true;
        }

        return $this->every($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Get all items except for those with the specified keys.
     *
     * @param mixed|self $keys
     * @return static
     */
    public function except($keys): Collection
    {
        if ($keys instanceof self) {
            $keys = $keys->all();
        } elseif (!is_array($keys)) {
            $keys = func_get_args();
        }

        return new static(Arr::except($this->items, $keys));
    }

    /**
     * Run a filter over each of the items.
     *
     * @param null|callable $callback
     * @return static
     */
    public function filter(?callable $callback = null): Collection
    {
        if ($callback) {
            return new static(Arr::where($this->items, $callback));
        }

        return new static(array_filter($this->items));
    }

    /**
     * Apply the callback if the value is truthy.
     *
     * @param mixed $value
     * @param callable $callback
     * @param null|callable $default
     * @return mixed|static
     */
    public function when($value, callable $callback, ?callable $default = null)
    {
        if ($value) {
            return $callback($this, $value);
        }
        if ($default) {
            return $default($this, $value);
        }

        return $this;
    }

    /**
     * Apply the callback if the collection is empty.
     *
     * @param callable $callback
     * @param null|callable $default
     * @return mixed|static
     */
    public function whenEmpty(callable $callback, ?callable $default = null)
    {
        return $this->when($this->isEmpty(), $callback, $default);
    }

    /**
     * Apply the callback if the collection is not empty.
     *
     * @param callable $callback
     * @param null|callable $default
     * @return mixed|static
     */
    public function whenNotEmpty(callable $callback, ?callable $default = null)
    {
        return $this->when($this->isNotEmpty(), $callback, $default);
    }

    /**
     * Apply the callback if the value is falsy.
     *
     * @param mixed $value
     * @param callable $callback
     * @param null|callable $default
     * @return mixed|static
     */
    public function unless($value, callable $callback, ?callable $default = null)
    {
        return $this->when(!$value, $callback, $default);
    }

    /**
     * Apply the callback unless the collection is empty.
     *
     * @param callable $callback
     * @param null|callable $default
     * @return mixed|static
     */
    public function unlessEmpty(callable $callback, ?callable $default = null)
    {
        return $this->whenNotEmpty($callback, $default);
    }

    /**
     * Apply the callback unless the collection is not empty.
     *
     * @param callable $callback
     * @param null|callable $default
     * @return mixed|static
     */
    public function unlessNotEmpty(callable $callback, ?callable $default = null)
    {
        return $this->whenEmpty($callback, $default);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed $operator
     * @param mixed $value
     * @return static
     */
    public function where(string $key, $operator = null, $value = null): Collection
    {
        return $this->filter($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function whereStrict(string $key, $value): Collection
    {
        return $this->where($key, '===', $value);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed $values
     * @param bool $strict
     * @return static
     */
    public function whereIn(string $key, $values, bool $strict = false): Collection
    {
        $values = $this->getArrayableItems($values);

        return $this->filter(static function ($item) use ($key, $values, $strict) {
            return in_array(data_get($item, $key), $values, $strict);
        });
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param string $key
     * @param mixed $values
     * @return static
     */
    public function whereInStrict(string $key, $values): Collection
    {
        return $this->whereIn($key, $values, true);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed $values
     * @param bool $strict
     * @return static
     */
    public function whereNotIn(string $key, $values, bool $strict = false): Collection
    {
        $values = $this->getArrayableItems($values);

        return $this->reject(static function ($item) use ($key, $values, $strict) {
            return in_array(data_get($item, $key), $values, $strict);
        });
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param string $key
     * @param mixed $values
     * @return static
     */
    public function whereNotInStrict(string $key, $values): Collection
    {
        return $this->whereNotIn($key, $values, true);
    }

    /**
     * Filter the items, removing any items that don't match the given type.
     *
     * @param string $type
     * @return static
     */
    public function whereInstanceOf(string $type): Collection
    {
        return $this->filter(static function ($value) use ($type) {
            return $value instanceof $type;
        });
    }

    /**
     * Get the first item from the collection.
     *
     * @param null|callable $callback
     * @param mixed $default
     * @return mixed
     */
    public function first(?callable $callback = null, $default = null)
    {
        return Arr::first($this->items, $callback, $default);
    }

    /**
     * Get the first item by the given key value pair.
     *
     * @param string $key
     * @param mixed $operator
     * @param mixed $value
     * @return mixed
     */
    public function firstWhere(string $key, $operator, $value = null)
    {
        return $this->first($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Get a flattened array of the items in the collection.
     *
     * @param float|int $depth
     * @return static
     */
    public function flatten($depth = INF): Collection
    {
        return new static(Arr::flatten($this->items, $depth));
    }

    /**
     * Flip the items in the collection.
     *
     * @return static
     */
    public function flip(): Collection
    {
        return new static(array_flip($this->items));
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param array|string $keys
     * @return $this
     */
    public function forget($keys): self
    {
        foreach ((array)$keys as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->items[$key];
        }

        return value($default);
    }

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param array|callable|string $groupBy
     * @param bool $preserveKeys
     * @return static
     */
    public function groupBy($groupBy, bool $preserveKeys = false): Collection
    {
        if (is_array($groupBy)) {
            $nextGroups = $groupBy;
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $groupBy = array_shift($nextGroups);
        }
        $groupBy = $this->valueRetriever($groupBy);
        $results = [];
        foreach ($this->items as $key => $value) {
            $groupKeys = $groupBy($value, $key);
            if (!is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }
            foreach ($groupKeys as $groupKey) {
                $groupKey = is_bool($groupKey) ? (int)$groupKey : $groupKey;
                if (!array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static;
                }
                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }
        }
        $result = new static($results);
        if (!empty($nextGroups)) {
            return $result->map->groupBy($nextGroups, $preserveKeys);
        }

        return $result;
    }

    /**
     * Key an associative array by a field or using a callback.
     *
     * @param callable|string $keyBy
     * @return static
     */
    public function keyBy($keyBy): Collection
    {
        $keyBy = $this->valueRetriever($keyBy);
        $results = [];
        foreach ($this->items as $key => $item) {
            $resolvedKey = $keyBy($item, $key);
            if (is_object($resolvedKey)) {
                $resolvedKey = (string)$resolvedKey;
            }
            $results[$resolvedKey] = $item;
        }

        return new static($results);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key): bool
    {
        $keys = is_array($key) ? $key : func_get_args();
        foreach ($keys as $value) {
            if (!$this->offsetExists($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Concatenate values of a given key as a string.
     *
     * @param string $value
     * @param null|string $glue
     * @return string
     */
    public function implode(string $value, ?string $glue = null): string
    {
        $first = $this->first();
        if (is_array($first) || is_object($first)) {
            return implode($glue ?? '', $this->pluck($value)->all());
        }

        return implode($value, $this->items);
    }

    /**
     * Intersect the collection with the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function intersect($items): Collection
    {
        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Intersect the collection with the given items by key.
     *
     * @param mixed $items
     * @return static
     */
    public function intersectByKeys($items): Collection
    {
        return new static(array_intersect_key(
            $this->items,
            $this->getArrayableItems($items)
        ));
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys(): Collection
    {
        return new static(array_keys($this->items));
    }

    /**
     * Get the last item from the collection.
     *
     * @param null|callable $callback
     * @param mixed $default
     * @return mixed
     */
    public function last(?callable $callback = null, $default = null)
    {
        return Arr::last($this->items, $callback, $default);
    }

    /**
     * Get the values of a given key.
     *
     * @param array|string $value
     * @param null|string $key
     * @return static
     */
    public function pluck($value, ?string $key = null): Collection
    {
        return new static(Arr::pluck($this->items, $value, $key));
    }

    /**
     * Run a map over each of the items.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): Collection
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * Run a map over each nested chunk of items.
     *
     * @param callable $callback
     * @return static
     */
    public function mapSpread(callable $callback): Collection
    {
        return $this->map(static function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * Run a dictionary map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param callable $callback
     * @return static
     */
    public function mapToDictionary(callable $callback): Collection
    {
        $dictionary = [];
        foreach ($this->items as $key => $item) {
            $pair = $callback($item, $key);
            $key = key($pair);
            $value = reset($pair);
            if (!isset($dictionary[$key])) {
                $dictionary[$key] = [];
            }
            $dictionary[$key][] = $value;
        }

        return new static($dictionary);
    }

    /**
     * Run a grouping map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param callable $callback
     * @return static
     */
    public function mapToGroups(callable $callback): Collection
    {
        return $this->mapToDictionary($callback)->map([$this, 'make']);
    }

    /**
     * Run an associative map over each of the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param callable $callback
     * @return static
     */
    public function mapWithKeys(callable $callback): Collection
    {
        $result = [];
        foreach ($this->items as $key => $value) {
            $assoc = $callback($value, $key);
            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
            }
        }

        return new static($result);
    }

    /**
     * Map a collection and flatten the result by a single level.
     *
     * @param callable $callback
     * @return static
     */
    public function flatMap(callable $callback): Collection
    {
        return $this->map($callback)->collapse();
    }

    /**
     * Map the values into a new class.
     *
     * @param string $class
     * @return static
     */
    public function mapInto(string $class): Collection
    {
        return $this->map(static function ($value, $key) use ($class) {
            return new $class($value, $key);
        });
    }

    /**
     * Get the max value of a given key.
     *
     * @param null|callable|string $callback
     * @return mixed
     */
    public function max($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        return $this->filter(static function ($value) {
            return $value !== null;
        })->reduce(static function ($result, $item) use ($callback) {
            $value = $callback($item);

            return $result === null || $value > $result ? $value : $result;
        });
    }

    /**
     * Merge the collection with the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function merge($items): Collection
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Create a collection by using this collection for keys and another for its values.
     *
     * @param mixed $values
     * @return static
     */
    public function combine($values): Collection
    {
        return new static(array_combine($this->all(), $this->getArrayableItems($values)));
    }

    /**
     * Union the collection with the given items.
     *
     * @param mixed $items
     * @return static
     */
    public function union($items): Collection
    {
        return new static($this->items + $this->getArrayableItems($items));
    }

    /**
     * Get the min value of a given key.
     *
     * @param null|callable|string $callback
     * @return mixed
     */
    public function min($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        return $this->map(static function ($value) use ($callback) {
            return $callback($value);
        })->filter(static function ($value) {
            return $value !== null;
        })->reduce(static function ($result, $value) {
            return $result === null || $value < $result ? $value : $result;
        });
    }

    /**
     * Create a new collection consisting of every n-th element.
     *
     * @param int $step
     * @param int $offset
     * @return static
     */
    public function nth(int $step, int $offset = 0): Collection
    {
        $new = [];
        $position = 0;
        foreach ($this->items as $item) {
            if ($position % $step === $offset) {
                $new[] = $item;
            }
            $position++;
        }

        return new static($new);
    }

    /**
     * Get the items with the specified keys.
     *
     * @param mixed $keys
     * @return static
     */
    public function only($keys): Collection
    {
        if ($keys === null) {
            return new static($this->items);
        }
        if ($keys instanceof self) {
            $keys = $keys->all();
        }
        $keys = is_array($keys) ? $keys : func_get_args();

        return new static(Arr::only($this->items, $keys));
    }

    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param int $page
     * @param int $perPage
     * @return static
     */
    public function forPage(int $page, int $perPage): Collection
    {
        $offset = max(0, ($page - 1) * $perPage);

        return $this->slice($offset, $perPage);
    }

    /**
     * Partition the collection into two arrays using the given callback or key.
     *
     * @param callable|string $key
     * @param mixed $operator
     * @param mixed $value
     * @return static
     */
    public function partition($key, $operator = null, $value = null): Collection
    {
        $partitions = [new static, new static];
        $callback = func_num_args() === 1
            ? $this->valueRetriever($key)
            : $this->operatorForWhere(...func_get_args());
        foreach ($this->items as $k => $item) {
            $partitions[(int)!$callback($item, $k)][$k] = $item;
        }

        return new static($partitions);
    }

    /**
     * Pass the collection to the given callback and return the result.
     *
     * @param callable $callback
     * @return mixed
     */
    public function pipe(callable $callback)
    {
        return $callback($this);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param mixed $value
     * @param mixed $key
     * @return $this
     */
    public function prepend($value, $key = null): self
    {
        $this->items = Arr::prepend($this->items, $value, $key);

        return $this;
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param mixed $value
     * @return $this
     */
    public function push($value): self
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * Push all of the given items onto the collection.
     *
     * @param array|\Traversable $source
     * @return static
     */
    public function concat($source): Collection
    {
        $result = new static($this);
        foreach ($source as $item) {
            $result->push($item);
        }

        return $result;
    }

    /**
     * Get and remove an item from the collection.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->items, $key, $default);
    }

    /**
     * Put an item in the collection by key.
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function put($key, $value): self
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * Get one or a specified number of items randomly from the collection.
     *
     * @param null|int $number
     * @throws \InvalidArgumentException
     * @return mixed|static
     *
     */
    public function random(?int $number = null)
    {
        if ($number === null) {
            return Arr::random($this->items);
        }

        return new static(Arr::random($this->items, $number));
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param callable|mixed $callback
     * @return static
     */
    public function reject($callback): Collection
    {
        if ($this->useAsCallable($callback)) {
            return $this->filter(static function ($value, $key) use ($callback) {
                return !$callback($value, $key);
            });
        }

        return $this->filter(static function ($item) use ($callback) {
            /** @noinspection TypeUnsafeComparisonInspection */
            return $item != $callback;
        });
    }

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse(): Collection
    {
        return new static(array_reverse($this->items, true));
    }

    /**
     * Search the collection for a given value and return the corresponding key if successful.
     *
     * @param mixed $value
     * @param bool $strict
     * @return false|int|string
     */
    public function search($value, bool $strict = false)
    {
        if (!$this->useAsCallable($value)) {
            return array_search($value, $this->items, $strict);
        }
        foreach ($this->items as $key => $item) {
            if ($value($item, $key)) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Shuffle the items in the collection.
     *
     * @param null|int $seed
     * @throws \Exception
     * @return static
     */
    public function shuffle(?int $seed = null): Collection
    {
        return new static(Arr::shuffle($this->items, $seed));
    }

    /**
     * Slice the underlying collection array.
     *
     * @param int $offset
     * @param null|int $length
     * @return static
     */
    public function slice(int $offset, ?int $length = null): Collection
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Split a collection into a certain number of groups.
     *
     * @param int $numberOfGroups
     * @return static
     */
    public function split(int $numberOfGroups): Collection
    {
        if ($this->isEmpty()) {
            return new static;
        }
        $groups = new static;
        $groupSize = floor($this->count() / $numberOfGroups);
        $remain = $this->count() % $numberOfGroups;
        $start = 0;
        for ($i = 0; $i < $numberOfGroups; $i++) {
            $size = $groupSize;
            if ($i < $remain) {
                $size++;
            }
            if ($size) {
                $groups->push(new static(array_slice($this->items, $start, $size)));
                $start += $size;
            }
        }

        return $groups;
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param int $size
     * @return static
     */
    public function chunk(int $size): Collection
    {
        if ($size <= 0) {
            return new static;
        }
        $chunks = [];
        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
     * Sort through each item with a callback.
     *
     * @param null|callable $callback
     * @return static
     */
    public function sort(?callable $callback = null): Collection
    {
        $items = $this->items;
        $callback
            ? uasort($items, $callback)
            : asort($items);

        return new static($items);
    }

    /**
     * Sort the collection using the given callback.
     *
     * @param callable|string $callback
     * @param int $options
     * @param bool $descending
     * @return static
     */
    public function sortBy($callback, int $options = SORT_REGULAR, bool $descending = false): Collection
    {
        $results = [];
        $callback = $this->valueRetriever($callback);
        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }
        $descending ? arsort($results, $options)
            : asort($results, $options);
        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        return new static($results);
    }

    /**
     * Sort the collection in descending order using the given callback.
     *
     * @param callable|string $callback
     * @param int $options
     * @return static
     */
    public function sortByDesc($callback, int $options = SORT_REGULAR): Collection
    {
        return $this->sortBy($callback, $options, true);
    }

    /**
     * Sort the collection keys.
     *
     * @param int $options
     * @param bool $descending
     * @return static
     */
    public function sortKeys(int $options = SORT_REGULAR, bool $descending = false): Collection
    {
        $items = $this->items;
        $descending ? krsort($items, $options) : ksort($items, $options);

        return new static($items);
    }

    /**
     * Sort the collection keys in descending order.
     *
     * @param int $options
     * @return static
     */
    public function sortKeysDesc(int $options = SORT_REGULAR): Collection
    {
        return $this->sortKeys($options, true);
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param int $offset
     * @param null|int $length
     * @param mixed $replacement
     * @return static
     */
    public function splice(int $offset, ?int $length = null, $replacement = []): Collection
    {
        if (func_num_args() === 1) {
            return new static(array_splice($this->items, $offset));
        }

        return new static(array_splice($this->items, $offset, $length, $replacement));
    }

    /**
     * Get the sum of the given values.
     *
     * @param null|callable|string $callback
     * @return mixed
     */
    public function sum($callback = null)
    {
        if ($callback === null) {
            return array_sum($this->items);
        }
        $callback = $this->valueRetriever($callback);

        return $this->reduce(static function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }

    /**
     * Take the first or last {$limit} items.
     *
     * @param int $limit
     * @return static
     */
    public function take(int $limit): Collection
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
    }

    /**
     * Pass the collection to the given callback and then return it.
     *
     * @param callable $callback
     * @return $this
     */
    public function tap(callable $callback): self
    {
        $callback(new static($this->items));

        return $this;
    }

    /**
     * Transform each item in the collection using a callback.
     *
     * @param callable $callback
     * @return $this
     */
    public function transform(callable $callback): self
    {
        $this->items = $this->map($callback)->all();

        return $this;
    }

    /**
     * Return only unique items from the collection array.
     *
     * @param null|callable|string $key
     * @param bool $strict
     * @return static
     */
    public function unique($key = null, bool $strict = false): Collection
    {
        $callback = $this->valueRetriever($key);
        $exists = [];

        return $this->reject(static function ($item, $key) use ($callback, $strict, &$exists) {
            if (in_array($id = $callback($item, $key), $exists, $strict)) {
                return true;
            }
            $exists[] = $id;

            return false;
        });
    }

    /**
     * Return only unique items from the collection array using strict comparison.
     *
     * @param null|callable|string $key
     * @return static
     */
    public function uniqueStrict($key = null): Collection
    {
        return $this->unique($key, true);
    }

    /**
     * Reset the keys on the underlying array.
     *
     * @return self
     */
    public function values(): self
    {
        return new static(array_values($this->items));
    }

    /**
     * Zip the collection together with one or more arrays.
     *
     * e.g. new Collection([1, 2, 3])->zip([4, 5, 6]);
     *      => [[1, 4], [2, 5], [3, 6]]
     *
     * @param array|self $items
     * @return static
     */
    public function zip($items): Collection
    {
        $arrayableItems = array_map(function ($items) {
            return $this->getArrayableItems($items);
        }, func_get_args());
        $params = array_merge([static function () {
            return new static(func_get_args());
        }, $this->items], $arrayableItems);

        return new static(array_map(...$params));
    }

    /**
     * Pad collection to the specified length with a value.
     *
     * @param int $size
     * @param mixed $value
     * @return static
     */
    public function pad(int $size, $value): Collection
    {
        return new static(array_pad($this->items, $size, $value));
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_map(static function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_map(static function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            }
            if ($value instanceof Jsonable) {
                try {
                    $value = json_decode($value->toJson(), true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $e) {
                    $value = '';
                }
            }
            if ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->items);
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        try {
            return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR | $options);
        } catch (JsonException $e) {
            return '';
        }
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get a CachingIterator instance.
     *
     * @param int $flags
     * @throws \Exception
     * @return \CachingIterator
     */
    public function getCachingIterator(int $flags = CachingIterator::CALL_TOSTRING): CachingIterator
    {
        return new CachingIterator($this->getIterator(), $flags);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get a base Support collection instance from this collection.
     *
     * @return self
     */
    public function toBase(): self
    {
        return new self($this);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Get an operator checker callback.
     *
     * @param string $key
     * @param null|mixed|string $operator
     * @param mixed $value
     * @return \Closure
     */
    protected function operatorForWhere(string $key, $operator = null, $value = null): callable
    {
        if (func_num_args() === 1) {
            $value = true;
            $operator = '=';
        }
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        return static function ($item) use ($key, $operator, $value) {
            $retrieved = data_get($item, $key);
            $strings = array_filter([$retrieved, $value], static function ($value) {
                return is_string($value) || (is_object($value) && method_exists($value, '__toString'));
            });
            if (count($strings) < 2 && count(array_filter([$retrieved, $value], 'is_object')) === 1) {
                return in_array($operator, ['!=', '<>', '!==']);
            }
            switch ($operator) {
                default:
                case '=':
                case '==':
                    /** @noinspection TypeUnsafeComparisonInspection */
                    return $retrieved == $value;
                case '!=':
                case '<>':
                    /** @noinspection TypeUnsafeComparisonInspection */
                    return $retrieved != $value;
                case '<':
                    return $retrieved < $value;
                case '>':
                    return $retrieved > $value;
                case '<=':
                    return $retrieved <= $value;
                case '>=':
                    return $retrieved >= $value;
                case '===':
                    return $retrieved === $value;
                case '!==':
                    return $retrieved !== $value;
            }
        };
    }

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param mixed $value
     * @return bool
     */
    protected function useAsCallable($value): bool
    {
        return !is_string($value) && is_callable($value);
    }

    /**
     * Get a value retrieving callback.
     *
     * @param callable|string $value
     * @return callable
     */
    protected function valueRetriever($value): callable
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return static function ($item) use ($value) {
            return data_get($item, $value);
        };
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param mixed $items
     * @return array
     */
    protected function getArrayableItems($items): array
    {
        if (is_array($items)) {
            return $items;
        }
        if ($items instanceof self) {
            return $items->all();
        }
        if ($items instanceof Arrayable) {
            return $items->toArray();
        }
        if ($items instanceof Jsonable) {
            try {
                return json_decode($items->toJson(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                return [];
            }
        }

        if ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        }

        if ($items instanceof Traversable) {
            return iterator_to_array($items);
        }

        return (array)$items;
    }
}
