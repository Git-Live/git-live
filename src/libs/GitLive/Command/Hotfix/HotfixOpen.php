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

namespace GitLive\Command\Hotfix;


use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\HotfixDriver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HotfixOpen
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
class HotfixOpen extends CommandBase
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('hotfix:open')
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Support preparation of a new production hotfix/.') . __("Allow for minor bug fixes and preparing meta-data for a hotfix"))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(__('Support preparation of a new production hotfix/.') . __("Allow for minor bug fixes and preparing meta-data for a hotfix"))
            ->addArgument('name', InputArgument::OPTIONAL, 'hotfix_name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \GitLive\Driver\Exception
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);


        App::make(HotfixDriver::class)->buildOpen($input->getArgument('name'));

    }
}