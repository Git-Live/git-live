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
use Exception;
use GitLive\GitBase;
use ReflectionClass;
use ReflectionException;
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
    protected static array $container = [];
    protected static array $contextContainer = [];

    protected array $buildStack = [];

    protected array $with = [];

    /**
     * @return void
     */
    public static function reset(): void
    {
        static::$container = [];
        static::$contextContainer = [];
    }

    /**
     * @return array
     */
    public static function getContainers(): array
    {
        return static::$container;
    }

    /**
     * @return array
     */
    public static function getContextContainers(): array
    {
        return static::$contextContainer;
    }

    /**
     * @param string $concrete
     * @param mixed $class
     */
    public static function bindContext(string $concrete, $class): void
    {
        static::$contextContainer[$concrete] = $class;
    }

    /**
     * @param string $interface
     * @param mixed $class
     */
    public static function bind(string $interface, $class): void
    {
        static::$container[$interface] = $class;
    }

    /**
     * @param array $with
     */
    public function setWith(array $with): void
    {
        $this->with = $with;
    }

    /**
     * Instantiate a concrete instance of the given type.
     *
     * @param Closure|string $concrete
     * @throws \ReflectionException
     * @return mixed
     */
    public function build($concrete)
    {
        if ($concrete instanceof Closure) {
            $concrete = $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            return $this->notInstantiable($concrete);
        }

        $this->buildStack[] = $concrete;
        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            array_pop($this->buildStack);

            /**
             * @var GitBase $res
             */
            $res = new $concrete;
        } else {
            $dependencies = $constructor->getParameters();

            $instances = $this->resolveDependencies(
                $dependencies
            );

            array_pop($this->buildStack);
            /**
             * @var GitBase $res
             */
            $res = $reflector->newInstanceArgs($instances);
        }

        try {
            $boot = $reflector->getMethod('boot');
        } catch (Exception $exception) {
            $boot = null;
        }

        if ($boot) {
            $res->boot();
        }

        return $res;
    }

    /**
     * @param $concrete
     * @throws \ReflectionException
     * @return mixed
     */
    public function notInstantiable($concrete)
    {
        if (!isset(static::$container[$concrete])) {
            throw new ReflectionException($concrete . ' is not bind.');
        }
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
    protected function resolveDependencies(array $dependencies): array
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

            $typeHint = $dependency->getType();
            $results[] = $typeHint === null || $typeHint->isBuiltin()
                ? $this->resolvePrimitive($dependency)
                : $this->build($typeHint->getName());
        }

        return $results;
    }

    /**
     * Determine if the given dependency has a parameter override.
     *
     * @param \ReflectionParameter $dependency
     * @return bool
     */
    protected function hasParameterOverride(ReflectionParameter $dependency): bool
    {
        return array_key_exists(
            $dependency->name,
            $this->with
        );
    }

    /**
     * Get a parameter override for a dependency.
     *
     * @param \ReflectionParameter $dependency
     * @return mixed
     */
    protected function getParameterOverride(ReflectionParameter $dependency)
    {
        return $this->with[$dependency->name];
    }

    /**
     * Resolve a non-class hinted primitive dependency.
     *
     * @param \ReflectionParameter $parameter
     * @return mixed
     */
    protected function resolvePrimitive(ReflectionParameter $parameter)
    {
        if (($concrete = $this->getContextualConcrete('$' . $parameter->name)) !== null) {
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
    protected function getContextualConcrete(string $concrete)
    {
        return static::$contextContainer[$concrete] ?? null;
    }
}
