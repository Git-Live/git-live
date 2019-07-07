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

use App;
use GitLive\Application\Container;
use GitLive\Driver\InitDriver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
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
class InitCommand extends CommandBase
{
    protected static $signature_name = 'init';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Initialize git live flow.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Initialize git live flow.'))
            ->addArgument('clone_repository', InputArgument::OPTIONAL, 'Only your remote repository.')
            ->addArgument('upstream_repository', InputArgument::OPTIONAL, 'Common remote repository.')
            ->addArgument('deploy_repository', InputArgument::OPTIONAL, 'Deploy remote repository.')
            ->addArgument('clone_dir', InputArgument::OPTIONAL, 'Clone path.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \GitLive\Driver\Exception
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        APP::make(InitDriver::class)->init($input);
    }
}
