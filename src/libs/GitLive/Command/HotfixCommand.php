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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HotfixCommand
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
class HotfixCommand extends CommandBase
{
    protected static $defaultName = 'hotfix';

    /**
     * {@inheritdoc}
     * @throws \ErrorException
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Alias to "hotfix: *" tasks.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$defaultName, $this->getDescription()))
            ->addArgument(
                'task',
                InputArgument::REQUIRED,
                'git live hotfix task'
            )
            ->addArgument(
                'release_name',
                InputArgument::OPTIONAL,
                'git live hotfix task option'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        switch ($input->getArgument('task')) {
            case 'open':
                $command = $this->getApplication()->find('hotfix:open');

                $arguments = [];

                if ($input->getArgument('release_name')) {
                    $arguments['name'] = $input->getArgument('release_name');
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'close':
                $command = $this->getApplication()->find('hotfix:close');

                $arguments = [];

                if ($input->getArgument('release_name')) {
                    $arguments['name'] = $input->getArgument('release_name');
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'close-force':
                $command = $this->getApplication()->find('hotfix:close');

                $arguments = [
                    'force' => true,
                ];

                if ($input->getArgument('release_name')) {
                    $arguments['name'] = $input->getArgument('release_name');
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'sync':
                $command = $this->getApplication()->find('hotfix:sync');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'pull':
                $command = $this->getApplication()->find('hotfix:pull');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'push':
                $command = $this->getApplication()->find('hotfix:push');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'track':
                $command = $this->getApplication()->find('hotfix:track');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'state':
                $command = $this->getApplication()->find('hotfix:state');

                $arguments = [
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'state-all':
                $command = $this->getApplication()->find('hotfix:state');

                $arguments = [
                    'with-merge-commit' => true,
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'is':
                $command = $this->getApplication()->find('hotfix:is');

                $arguments = [
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'destroy':
                $command = $this->getApplication()->find('hotfix:destroy');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
            case 'destroy-clean':
                $command = $this->getApplication()->find('hotfix:destroy');

                $arguments = [
                    'remove-local' => true,
                ];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);
        }

        return Command::SUCCESS;
    }
}
