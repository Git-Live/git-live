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

namespace GitLive\Command\Config;

use GitLive\Application\Container;
use GitLive\Application\Facade as App;
use GitLive\Command\CommandBase;
use GitLive\Driver\ConfigDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetCommand
 *
 * @category   GitCommand
 * @package    GitLive\Command\Config
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
class SetCommand extends CommandBase
{
    protected static $defaultName = 'config:set';

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
            ->setDescription(__('Write the setting for gitlive in the config file.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$defaultName, $this->getDescription()))
            ->addArgument('name', InputArgument::REQUIRED, 'Setting items.')
            ->addArgument('value', InputArgument::REQUIRED, 'Setting Values.')
            ->addOption(
                'global',
                null,
                InputOption::VALUE_NONE,
                __('For writing options: write to global ~/.gitconfig file rather than the repository .git/config, write to $XDG_CONFIG_HOME/git/config file if this file exists and the ~/.gitconfig file does not.')
                . __('For reading options: read only from global ~/.gitconfig and from $XDG_CONFIG_HOME/git/config rather than from all available files.')
                . __('See also the section called "FILES".')
            )
            ->addOption(
                'system',
                null,
                InputOption::VALUE_NONE,
                __('For writing options: write to system-wide $(prefix)/etc/gitconfig rather than the repository .git/config.')
                . __('For reading options: read only from system-wide $(prefix)/etc/gitconfig rather than from all available files.')
                . __('See also the section called "FILES".')
            )
            ->addOption(
                'local',
                null,
                InputOption::VALUE_NONE,
                __('For writing options: write to the repository .git/config file. This is the default behavior.')
                . __('For reading options: read only from the repository .git/config rather than from all available files.')
                . __('See also the section called "FILES".')
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \ErrorException
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $ConfigDriver = App::make(ConfigDriver::class);
        if ($input->getOption('global')) {
            $ConfigDriver->setGlobalParameter($input->getArgument('name'), $input->getArgument('value'));

            return Command::SUCCESS;
        }
        if ($input->getOption('system')) {
            $ConfigDriver->setSystemParameter($input->getArgument('name'), $input->getArgument('value'));

            return Command::SUCCESS;
        }

        $ConfigDriver->setLocalParameter($input->getArgument('name'), $input->getArgument('value'));

        return Command::SUCCESS;
    }
}
