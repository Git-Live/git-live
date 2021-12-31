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

namespace GitLive\Command\Release;

use GitLive\Application\Facade as App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\ReleaseDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReleasePushCommand extends CommandBase
{
    protected static $signature_name = 'release:push';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Push upstream/release and deploy/release.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$signature_name, $this->getDescription()));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \GitLive\Driver\Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        App::make(ReleaseDriver::class)->buildPush();
    }
}
