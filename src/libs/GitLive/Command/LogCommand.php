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

namespace GitLive\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LogCommand
 *
 * @category   GitCommand
 * @package    GitLive\Command
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
class LogCommand extends CommandBase
{
    protected static $signature_name = 'log';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Alias to "log: *" tasks.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Alias to "log: *" tasks.'))
            ->addArgument(
                'task',
                InputArgument::REQUIRED,
                'git live log task'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('task')) {
            case 'develop':
                $command = $this->getApplication()->find('log:develop');

                $arguments = [
                    '--name-status' => 'name-status',
                    '--pretty' => 'fuller',
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'master':
                $command = $this->getApplication()->find('log:master');

                $arguments = [
                    '--name-status' => 'name-status',
                    '--pretty' => 'fuller',
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
        }

        return 0;
    }
}
