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

use App;
use GitLive\Application\Container;
use GitLive\Command\CommandBase;
use GitLive\Driver\FeatureDriver;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FeaturePushCommand
 *
 * @category   GitCommand
 * @package    GitLive\Command\Feature
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
class FeaturePushCommand extends CommandBase
{
    protected static $signature_name = 'feature:push';

    /**
     * {@inheritdoc}
     * @throws \ErrorException
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription(__('Safe push to origin repository.'))
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp(resource()->help(self::$signature_name, $this->getDescription()))
            ->addArgument('feature_name', InputArgument::OPTIONAL, 'feature name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Container::bindContext('$input', $input);
        Container::bindContext('$output', $output);

        $FeatureDriver = App::make(FeatureDriver::class);

        $res = $FeatureDriver->featurePush($input->getArgument('feature_name'));

        $output->writeln($res);
    }
}
