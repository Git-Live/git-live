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
use GitLive\Driver\FetchDriver;
use GitLive\Driver\FireDriver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleanCommand
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
 * @since      2018/11/26
 */
class FireCommand extends CommandBase
{
    protected static $signature_name = 'fire';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Add all the changed files, commit to the new branch, push to origin, and protect the changes.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('It is executed at the time of disaster such as earthquake or fire. Add all the changed files, commit to the new branch, push to origin, and protect the changes.'))
            ->addArgument(
                'message',
                InputArgument::OPTIONAL,
                'commit message'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);
        App::make(FireDriver::class)->fire($input->getArgument('message') ?? '');

    }
}
