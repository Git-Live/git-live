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

namespace GitLive\Command\Branch;

use GitLive\Application\Facade as App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\BranchDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends CommandBase
{
    protected static $signature_name = 'branch:init';

    /**
     * {@inheritdoc}
     * @throws \ErrorException
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Will reset all additions and changes to the current branch.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$signature_name, $this->getDescription()))
            ->addOption('default', 'd', InputOption::VALUE_NONE, __('Use default branch naming conventions.'))
            ->addOption('force', 'f', InputOption::VALUE_NONE, __('Force setting of gitflow branches, even if already configured.'));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \ErrorException
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        App::make(BranchDriver::class)->init($input->getOption('default'), $input->getOption('force'));
    }
}
