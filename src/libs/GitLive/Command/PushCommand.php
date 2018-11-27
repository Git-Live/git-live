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
use GitLive\Driver\ConfigDriver;
use GitLive\Driver\FetchDriver;
use GitLive\GitCmdExecuter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushCommand extends CommandBase
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('push')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Push from the appropriate remote repository.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Pull from the appropriate remote repository.'));
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

        $FetchDriver = App::make(FetchDriver::class);
        $ConfigDriver = App::make(ConfigDriver::class);
        $branch = $FetchDriver->getSelfBranchRef();
        $remote = 'origin';

        if (strpos($branch, 'refs/heads' . $ConfigDriver->releasePrefix()) !== false || strpos($branch, 'refs/heads' . $ConfigDriver->hotfixPrefix()) !== false) {
            $remote = 'upstream';
        }

        App::make(GitCmdExecuter::class)->push($remote, $branch);
    }
}
