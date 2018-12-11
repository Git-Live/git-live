<?php
/**
 * .phpstorm.meta.php
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
 * @since      2018/11/23
 */

namespace PHPSTORM_META
{

    use GitLive\Driver\BranchDriver;
    use GitLive\Driver\ConfigDriver;
    use GitLive\Driver\DriverBase;
    use GitLive\Driver\FeatureDriver;
    use GitLive\Driver\HotfixDriver;
    use GitLive\Driver\InitDriver;
    use GitLive\Driver\LastestVersionDriver;
    use GitLive\Driver\LogDriver;
    use GitLive\Driver\MergeDriver;
    use GitLive\Driver\PullRequestDriver;
    use GitLive\Driver\ReleaseDriver;
    use GitLive\Driver\ResetDriver;
    use GitLive\Driver\UpdateDriver;

    override(\GitLive\Application\Facade::make(0), map([
        '' => '@',
        // custom mappings
        \GitLive\GitLive::class => \GitLive\GitLive::class,
        \GitLive\Service\CommandLineKernelService::class => \GitLive\Service\CommandLineKernelService::class,
        BranchDriver::class => BranchDriver::class,
        ConfigDriver::class=> ConfigDriver::class,
        FeatureDriver::class=> FeatureDriver::class,
        FeatureDriver::class => FeatureDriver::class,
        HotfixDriver::class => HotfixDriver::class,
        InitDriver::class => InitDriver::class,
        LogDriver::class => LogDriver::class,
        MergeDriver::class => MergeDriver::class,
        PullRequestDriver::class => PullRequestDriver::class,
        ReleaseDriver::class => ReleaseDriver::class,
        UpdateDriver::class => UpdateDriver::class,
        ResetDriver::class => ResetDriver::class,
        LastestVersionDriver::class => LastestVersionDriver::class,
    ]));


    override(DriverBase::Driver(0), map([
        '' => '@',
        // custom mappings
        'Branch' => BranchDriver::class,
        'Config' => ConfigDriver::class,
        'Feature' => FeatureDriver::class,
        'Fetch' => FeatureDriver::class,
        'Hotfix' => HotfixDriver::class,
        'Init' => InitDriver::class,
        'Log' => LogDriver::class,
        'Merge' => MergeDriver::class,
        'PullRequest' => PullRequestDriver::class,
        'Release' => ReleaseDriver::class,
        'Reset' => ResetDriver::class,
        'Update' => UpdateDriver::class,,
        'LastestVersion' => LastestVersionDriver::class,
    ]));
}