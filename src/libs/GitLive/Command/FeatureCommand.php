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
class FeatureCommand extends CommandBase
{
    protected static $signature_name = 'feature';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Alias to "feature: *" tasks.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Alias to "feature: *" tasks.'))
            ->addArgument(
                'task',
                InputArgument::REQUIRED,
                'git live feature task'
            )
            ->addArgument(
                'branch_name',
                InputArgument::OPTIONAL,
                'git live feature task option'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('task')) {
            case 'list':
                $command = $this->getApplication()->find('feature:list');

                $arguments = [];

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'status':
                $command = $this->getApplication()->find('feature:status');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'change':
                $command = $this->getApplication()->find('feature:change');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'close':
                $command = $this->getApplication()->find('feature:close');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'publish':
                $command = $this->getApplication()->find('feature:publish');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'pull':
                $command = $this->getApplication()->find('feature:pull');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'push':
                $command = $this->getApplication()->find('feature:push');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'start':
                $command = $this->getApplication()->find('feature:start');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }

                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
            case 'track':
                $command = $this->getApplication()->find('feature:track');

                $arguments = [];
                if ($input->getArgument('branch_name')) {
                    $arguments = [
                        'branch_name' => $input->getArgument('branch_name'),
                    ];
                }
                $greetInput = new ArrayInput($arguments);

                return $command->run($greetInput, $output);

                break;
        }

        return 0;
    }
}
