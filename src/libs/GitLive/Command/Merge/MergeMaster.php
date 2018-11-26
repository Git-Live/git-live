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

namespace GitLive\Driver\Merge;


use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\MergeDriver;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StateDevelop
 *
 * @category   GitCommand
 * @package    GitLive\Driver\Merge
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
class MergeMaster extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('merge:master')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Merge upstream master.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Merge upstream master.'));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        App::make(MergeDriver::class)->mergeMaster();

        return 0;
    }
}
