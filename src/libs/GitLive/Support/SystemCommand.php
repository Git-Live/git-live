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

namespace GitLive\Support;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SystemCommand
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
 * @since      2018/11/24
 */
class SystemCommand implements SystemCommandInterface
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * SystemCommand constructor.
     * @param $input
     * @param $output
     */
    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param string   $cmd
     * @param bool|int $verbosity
     * @return string
     */
    public function exec($cmd, $verbosity = 0)
    {
        if ($verbosity === true) {
            $this->output->writeln('<fg=green;options=bold>' . $cmd . '</>', OutputInterface::VERBOSITY_VERBOSE);
        } elseif ($verbosity === false) {
            $this->output->writeln('<fg=green;options=bold>' . $cmd . '</>');
        } else {
            $this->output->writeln('<fg=green;options=bold>' . $cmd . '</>', $verbosity);
        }

        $res = `$cmd`;

        if ($verbosity === false) {
            $verbosity = OutputInterface::VERBOSITY_NORMAL;
        } elseif ($verbosity === true) {
            $verbosity = OutputInterface::VERBOSITY_DEBUG;
        }

        $this->output->writeln($res, $verbosity);

        return $res;
    }
}
