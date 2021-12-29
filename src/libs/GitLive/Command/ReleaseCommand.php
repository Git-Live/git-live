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
 * Class FeatureCommand
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
class ReleaseCommand extends CommandBase
{
    protected static $signature_name = 'release';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Alias to "release: *" tasks.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Alias to "release: *" tasks.'))
            ->addArgument(
                'task',
                InputArgument::REQUIRED,
                'git live release task'
            )
            ->addArgument(
                'release_name',
                InputArgument::OPTIONAL,
                'git live release task option'
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
            case 'open':
                $command = $this->getApplication()->find('release:open');

                $arguments = [];

                if ($input->getArgument('release_name')) {
                    $arguments['name'] = $input->getArgument('release_name');
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'close':
                $command = $this->getApplication()->find('release:close');

                $arguments = [];

                if ($input->getArgument('release_name')) {
                    $arguments['name'] = $input->getArgument('release_name');
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'close-force':
                $command = $this->getApplication()->find('release:close');

                $arguments = [
                    'force' => true,
                ];

                if ($input->getArgument('release_name')) {
                    $arguments['name'] = $input->getArgument('release_name');
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'sync':
                $command = $this->getApplication()->find('release:sync');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'pull':
                $command = $this->getApplication()->find('release:pull');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'push':
                $command = $this->getApplication()->find('release:push');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'track':
                $command = $this->getApplication()->find('release:track');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'state':
                $command = $this->getApplication()->find('release:state');

                $arguments = [
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'state-all':
                $command = $this->getApplication()->find('release:state');

                $arguments = [
                    'with-merge-commit' => true,
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'is':
                $command = $this->getApplication()->find('release:is');

                $arguments = [
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'destroy':
                $command = $this->getApplication()->find('release:destroy');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'destroy-clean':
                $command = $this->getApplication()->find('release:destroy');

                $arguments = [
                    'remove-local' => true,
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
        }

        return 0;
    }
}
