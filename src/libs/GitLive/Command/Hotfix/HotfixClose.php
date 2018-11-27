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

namespace GitLive\Command\Hotfix;

use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\HotfixDriver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HotfixClose
 *
 * @category   GitCommand
 * @package    GitLive\Command\Hotfix
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
class HotfixClose extends CommandBase
{
    /**
     *
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('hotfix:close')
            // the short description shown while running "php bin/console list"
            ->setDescription(__("Finish up a hotfix.Merges the hotfix branch back into 'master'.Tags the hotfix with its name.Back-merges the hotfix into 'develop'.Removes the hotfix branch."))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__("Finish up a hotfix.Merges the hotfix branch back into 'master'.Tags the hotfix with its name.Back-merges the hotfix into 'develop'.Removes the hotfix branch."))
            ->addArgument('name', InputArgument::OPTIONAL, 'hotfix_name')
            ->addOption('force', 'f', InputOption::VALUE_NONE, __('Do not check develop repository.'));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        App::make(HotfixDriver::class)->buildClose(
            $input->getOption('force'),
            $input->getArgument('name')
        );
    }
}