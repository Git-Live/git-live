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

namespace GitLive\Command\Feature;

use GitLive\Application\Container;
use GitLive\Application\Facade as App;
use GitLive\Command\CommandBase;
use GitLive\Driver\FeatureDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeCommand extends CommandBase
{
    protected static $defaultName = 'feature:change';

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
            ->setDescription(__('Cheackout other feature branch.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$defaultName, $this->getDescription()))
            ->addArgument('feature_name', InputArgument::REQUIRED, __('feature name'))
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                __('When switching branches, proceed even if the index or the working tree differs from HEAD. This is used to
           throw away local changes.')
                . "\n\n"
                . __('When checking out paths from the index, do not fail upon unmerged entries; instead, unmerged entries are
           ignored.')
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featureChange($input->getArgument('feature_name'), $this->getOptions($input));

        $output->writeln($res);

        return Command::SUCCESS;
    }
}
