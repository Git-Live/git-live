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

namespace GitLive\Driver\Merge;

use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\MergeDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StateMasterCommand
 *
 * @category   GitCommand
 * @package    GitLive\Driver\Merge
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
class StateMasterCommand extends CommandBase
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('merge:state:master')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Prior confirmation of merge master.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Prior confirmation of merge master.'));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $res = App::make(MergeDriver::class)->stateMaster();

        if (empty($res)) {
            $output->writeln('Is not conflict.');

            return 0;
        }

        $output->writeln($res, OutputInterface::VERBOSITY_VERBOSE);
        $output->writeln('Conflict!!');

        return 0;
    }
}