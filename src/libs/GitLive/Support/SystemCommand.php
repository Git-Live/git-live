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

use GitLive\GitBase;
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
class SystemCommand extends GitBase implements SystemCommandInterface
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
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param string   $cmd
     * @param bool|int $verbosity
     * @param null     $output_verbosity
     * @return string
     */
    public function exec($cmd, $verbosity = 0, $output_verbosity = null)
    {
        if ($verbosity === true) {
            $this->output->writeln('<fg=cyan;options=bold>' . $cmd . '</>', OutputInterface::VERBOSITY_VERY_VERBOSE);
        } elseif ($verbosity === false) {
            $this->output->writeln('<fg=cyan;options=bold>' . $cmd . '</>');
        } else {
            $this->output->writeln('<fg=cyan;options=bold>' . $cmd . '</>', $verbosity);
        }

        $execute_cmd = $cmd . ' 2>&1';

        $this->output->writeln('<fg=yellow>' . $execute_cmd . '</>', OutputInterface::VERBOSITY_DEBUG);
        $res = `$execute_cmd`;

        $output_verbosity = $output_verbosity??$verbosity;

        if ($output_verbosity === false) {
            $output_verbosity = OutputInterface::VERBOSITY_NORMAL;
        } elseif ($output_verbosity === true) {
            $output_verbosity = OutputInterface::VERBOSITY_DEBUG;
        }

        $this->output->writeln($res, $output_verbosity);

        return $res;
    }
}
