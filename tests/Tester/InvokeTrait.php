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

namespace Tests\GitLive\Tester;

/**
 * Class InvokeTrait
 *
 * @category    Test
 * @package     JapaneseDate
 * @subpackage  Tests
 * @author      Suzunone<suzunone.eleven@gmail.com>
 * @version     GIT: $Id$
 * @link        https://github.com/suzunone/JapaneseDate
 * @see         https://github.com/suzunone/JapaneseDate
 * @since       Class available since Release 1.0.0
 * @codeCoverageIgnore
 */
trait InvokeTrait
{
    /**
     * @param object|string $instance
     * @param string $method_name
     * @param array $options
     * @throws \ReflectionException
     * @return mixed
     */
    public function invokeExecuteMethod($instance, string $method_name, array $options)
    {
        $reflection = new \ReflectionClass($instance);
        $method     = $reflection->getMethod($method_name);
        $method->setAccessible(true);

        return $method->invokeArgs($instance, $options);
    }

    /**
     * @param object|string $instance
     * @param string $property_name
     * @throws \ReflectionException
     * @return mixed
     */
    public function invokeGetProperty($instance, string $property_name)
    {
        $reflection = new \ReflectionClass($instance);
        $property   = $reflection->getProperty($property_name);
        $property->setAccessible(true);

        return $property->getValue($instance);
    }

    /**
     * @param object|string $instance
     * @param string $property_name
     * @param mixed $data
     * @throws \ReflectionException
     */
    public function invokeSetProperty($instance, string $property_name, $data)
    {
        $reflection = new \ReflectionClass($instance);
        $property   = $reflection->getProperty($property_name);
        $property->setAccessible(true);
        $property->setValue($instance, $data);
    }
}
