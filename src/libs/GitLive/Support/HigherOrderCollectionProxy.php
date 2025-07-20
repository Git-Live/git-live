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

use GitLive\GitBase;

/**
 * Class HigherOrderCollectionProxy
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
 * @since      2018-12-13
 * @mixin Collection
 */
class HigherOrderCollectionProxy extends GitBase
{
    /**
     * The collection being operated on.
     *
     * @var \GitLive\Support\Collection
     */
    protected Collection $collection;

    /**
     * The method being proxied.
     *
     * @var string
     */
    protected string $method;

    /**
     * Create a new proxy instance.
     *
     * @param \GitLive\Support\Collection $collection
     * @param string $method
     * @return void
     */
    public function __construct(Collection $collection, string $method)
    {
        $this->method = $method;
        $this->collection = $collection;
    }

    /** @noinspection MagicMethodsValidityInspection */
    /**
     * Proxy accessing an attribute onto the collection items.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->collection->{$this->method}(static function ($value) use ($key) {
            return is_array($value) ? $value[$key] : $value->{$key};
        });
    }

    /**
     * Proxy a method call onto the collection items.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->collection->{$this->method}(static function ($value) use ($method, $parameters) {
            return $value->{$method}(...$parameters);
        });
    }
}
