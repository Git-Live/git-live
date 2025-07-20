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

namespace Example;

/**
 * @coversNothing
 * @codeCoverageIgnore
 */
class BindTestContextExample
{
    public $text;
    public $bindTest;
    public $default_value;
    public $closure = 'fish';
    public $nothing = 'bird';

    public $is_boot = false;

    public function __construct(string $text, $bindTest = 'rabbit', $closure = '', $nothing = '', $default_value = '123456789')
    {
        $this->text = $text;
        $this->bindTest = $bindTest;
        $this->closure = $closure;
        $this->nothing = $nothing;
        $this->default_value = $default_value;
    }

    public function boot()
    {
        $this->is_boot = true;
    }
}
