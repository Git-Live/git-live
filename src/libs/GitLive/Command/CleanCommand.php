<?php
/**
 *
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
 * @since      2018/11/24
 */

namespace GitLive\Command;


use App;
use GitLive\Application\Container;
use GitLive\Driver\FetchDriver;
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
class CleanCommand extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('clean')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Will reset the branch before the last commit.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Will reset the branch before the last commit.'));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);


        App::make(FetchDriver::class)->clean();

    }
}