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

use App;;
use Exception;
use GitLive\Driver\LatestVersionDriver;
use GitLive\Support\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CommandBase
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
 * @since      2018/11/23
 */
abstract class CommandBase extends Command
{
    /**
     * @var string
     */
    protected static $signature_name = '';

    public static function getSignature(): string
    {
        return static::$signature_name;
    }

    /**
     * @param OutputInterface $output
     */
    public function updateChecker(OutputInterface $output)
    {
        try {
            if (App::make(LatestVersionDriver::class)->ckNewVersion()) {
                $output->writeln('Alert:' . __('An update to the Git Live is available. Run "git live self-update" to get the latest version.'));
            }
        } catch (Exception $exception) {
        }
    }

    protected function configure()
    {
        parent::configure();
        $this
            // the name of the command (the part after "bin/console")
            ->setName(static::$signature_name);
    }

    /**
     * @param InputInterface $input
     * @return \GitLive\Support\Collection
     */
    protected function getOptions(InputInterface $input): Collection
    {
        return collect(collect($input->getOptions())
            ->filter(static function ($item) {
                return !($item === false || $item === null);
            })
            ->map(static function ($item, $key) {
                if ($item === $key || $item === true) {
                    return '--' . $key;
                }

                return '--' . $key . '=' . $item;
            })->values()->toArray());
    }
}
