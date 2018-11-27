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

use Closure;
use ReflectionClass;
use ReflectionParameter;

/**
 * Class Container
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
class Container
{
    protected static $container = [];
    protected static $contextContainer = [];

    protected $buildStack;

    protected $with = [];

    public static function reset()
    {
        static::$container = [];
        static::$contextContainer = [];
    }

    /**
     * @return array
     */
    public static function getContainers()
    {
        return static::$container;
    }

    /**
     * @return array
     */
    public static function getContextContainers()
    {
        return static::$contextContainer;
    }

    /**
     * @param string $concrete
     * @param mixed  $class
     */
    public static function bindContext(string $concrete, $class)
    {
        static::$contextContainer[$concrete] = $class;
    }

    /**
     * @param string $interface
     * @param mixed  $class
     */
    public static function bind(string $interface, $class)
    {
        static::$container[$interface] = $class;
    }

    /**
     * @param array $with
     */
    public function setWith(array $with)
    {
        $this->with = $with;
    }

    /**
     * Instantiate a concrete instance of the given type.
     *
     * @param  Closure|string $concrete
     * @throws \ReflectionException
     * @return mixed
     */
    public function build($concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            return $this->notInstantiable($concrete);
        }

        $this->buildStack[] = $concrete;
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            array_pop($this->buildStack);

            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $instances = $this->resolveDependencies(
            $dependencies
        );

        array_pop($this->buildStack);

        return $reflector->newInstanceArgs($instances);
    }

    /**
     * @param $concrete
     * @throws \ReflectionException
     * @return mixed
     */
    public function notInstantiable($concrete)
    {
        $concrete = static::$container[$concrete];
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        return $this->build($concrete);
    }

    /**
     * @param array $dependencies
     * @throws \ReflectionException
     * @return array
     */
    protected function resolveDependencies(array $dependencies)
    {
        $results = [];
        /**
         * @var ReflectionParameter $dependency
         */
        foreach ($dependencies as $dependency) {
            if ($this->hasParameterOverride($dependency)) {
                $results[] = $this->getParameterOverride($dependency);

                continue;
            }

            /*
            $TypeHint = (string)$dependency->getType();

            if (isset(static::$container[$TypeHint])) {
                $results[] = $this->build($TypeHint);
                continue;
            }
            */

            $results[] = is_null($dependency->getClass())
                ? $this->resolvePrimitive($dependency)
                : $this->build($dependency->getClass()->name);
        }

        return $results;
    }

    /**
     * Determine if the given dependency has a parameter override.
     *
     * @param  \ReflectionParameter $dependency
     * @return bool
     */
    protected function hasParameterOverride($dependency)
    {
        return array_key_exists(
            $dependency->name,
            $this->with
        );
    }

    /**
     * Get a parameter override for a dependency.
     *
     * @param  \ReflectionParameter $dependency
     * @return mixed
     */
    protected function getParameterOverride($dependency)
    {
        return $this->with[$dependency->name];
    }

    /**
     * Resolve a non-class hinted primitive dependency.
     *
     * @param  \ReflectionParameter $parameter
     * @return mixed
     */
    protected function resolvePrimitive(ReflectionParameter $parameter)
    {
        if (!is_null($concrete = $this->getContextualConcrete('$' . $parameter->name))) {
            return $concrete instanceof Closure ? $concrete($this) : $concrete;
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        return null;
    }

    /**
     * @param string $concrete
     * @return null|mixed
     */
    protected function getContextualConcrete($concrete)
    {
        return static::$contextContainer[$concrete] ?? null;
    }
}