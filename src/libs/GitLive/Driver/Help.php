<?php
/**
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
 * @since      Class available since Release 1.0.0
 */
namespace GitLive\Driver;

/**
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
 * @since      Class available since Release 1.0.0
 */
class Help extends DriverBase
{

    /**
     * +-- バージョン表示
     *
     * @access      public
     * @return void
     * @codeCoverageIgnore
     */
    public function version()
    {
        $this->ncecho('Git Live version '.self::VERSION .' - '.self::VERSION_CODENAME."\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- ヘルプの表示
     *
     * @access      public
     * @return void
     * @codeCoverageIgnore
     */
    public function help()
    {
        $indent = '    ';
        $this->ncecho('Git Live version '.self::VERSION."\n");
        $this->ncecho("NAME\n");
        $this->ncecho("{$indent}{$indent}git-live - ".__('Supports safe and efficient repository operation.')."\n");
        $this->ncecho("SYNOPSIS\n");
        $this->ncecho("{$indent}{$indent}git live feature start <feature name>\n");
        $this->ncecho("{$indent}{$indent}git live feature change <feature name>\n");
        $this->ncecho("{$indent}{$indent}git live feature checkout <feature name>\n");
        $this->ncecho("{$indent}{$indent}git live feature list\n");
        $this->ncecho("{$indent}{$indent}git live feature publish\n");
        $this->ncecho("{$indent}{$indent}git live feature track\n");
        $this->ncecho("{$indent}{$indent}git live feature push\n");
        $this->ncecho("{$indent}{$indent}git live feature pull\n");
        $this->ncecho("{$indent}{$indent}git live feature close\n");

        $this->ncecho("{$indent}{$indent}git live pr track\n");
        $this->ncecho("{$indent}{$indent}git live pr pull\n");
        $this->ncecho("{$indent}{$indent}git live pr merge\n");
        $this->ncecho("{$indent}{$indent}git live pr feature start-soft <pull request number> <feature name>\n");
        $this->ncecho("{$indent}{$indent}git live pr feature start <pull request number> <feature name>\n");


        $this->ncecho("{$indent}{$indent}git live hotfix open <release name>\n");
        $this->ncecho("{$indent}{$indent}git live hotfix close\n");
        $this->ncecho("{$indent}{$indent}git live hotfix sync\n");
        $this->ncecho("{$indent}{$indent}git live hotfix state\n");
        $this->ncecho("{$indent}{$indent}git live hotfix state-all\n");
        $this->ncecho("{$indent}{$indent}git live hotfix is\n");
        $this->ncecho("{$indent}{$indent}git live hotfix track\n");
        $this->ncecho("{$indent}{$indent}git live hotfix pull\n");
        $this->ncecho("{$indent}{$indent}git live hotfix push\n");
        $this->ncecho("{$indent}{$indent}git live hotfix destroy\n");
        $this->ncecho("{$indent}{$indent}git live hotfix destroy-clean\n");

        $this->ncecho("{$indent}{$indent}git live release open <release tag name>\n");
        $this->ncecho("{$indent}{$indent}git live release close\n");
        $this->ncecho("{$indent}{$indent}git live release close-force\n");
        $this->ncecho("{$indent}{$indent}git live release sync\n");
        $this->ncecho("{$indent}{$indent}git live release state\n");
        $this->ncecho("{$indent}{$indent}git live release state-all\n");
        $this->ncecho("{$indent}{$indent}git live release is\n");
        $this->ncecho("{$indent}{$indent}git live release track\n");
        $this->ncecho("{$indent}{$indent}git live release pull\n");
        $this->ncecho("{$indent}{$indent}git live release push\n");
        $this->ncecho("{$indent}{$indent}git live release destroy\n");
        $this->ncecho("{$indent}{$indent}git live release destroy-clean\n");

        $this->ncecho("{$indent}{$indent}git live pull\n");
        $this->ncecho("{$indent}{$indent}git live push\n");
        $this->ncecho("{$indent}{$indent}git live clean\n");
        $this->ncecho("{$indent}{$indent}git live update\n");

        $this->ncecho("{$indent}{$indent}git live merge develop\n");
        $this->ncecho("{$indent}{$indent}git live merge master\n");

        $this->ncecho("{$indent}{$indent}git live log develop\n");
        $this->ncecho("{$indent}{$indent}git live log master\n");

        $this->ncecho("{$indent}{$indent}git live init\n");
        $this->ncecho("{$indent}{$indent}git live start\n");
        $this->ncecho("{$indent}{$indent}git live restart\n");

        $this->ncecho("OPTIONS\n");
        $this->ncecho("{$indent}{$indent}feature start <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Create a new feature branch.(From upstream/develop)")."\n");
        $this->ncecho("{$indent}{$indent}feature checkout <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Checkout other feature branch.')."\n");
        $this->ncecho("{$indent}{$indent}feature change <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Alias of "git live feature checkout".')."\n");
        $this->ncecho("{$indent}{$indent}feature list\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Show feature list.')."\n");
        $this->ncecho("{$indent}{$indent}feature publish\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Safe push to upstream repository.")."\n");
        $this->ncecho("{$indent}{$indent}feature track <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Safe checkout feature branch from upstream repository.")."\n");
        $this->ncecho("{$indent}{$indent}feature push\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Safe push to origin repository.")."\n");
        $this->ncecho("{$indent}{$indent}feature pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Safe pull to upstream repository.")."\n");
        $this->ncecho("{$indent}{$indent}feature close\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Remove feature branch, from all repository.")."\n");

        $this->ncecho("{$indent}{$indent}pr track <pull request number>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("'Checkout pull request locally.")."\n");
        $this->ncecho("{$indent}{$indent}pr pull \n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Pull pull request locally.')."\n");
        $this->ncecho("{$indent}{$indent}pr merge <pull request number>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Merge pull request locally.')."\n");
        $this->ncecho("{$indent}{$indent}pr feature start-soft <pull request number> <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Feature start from pull request.')."\n");
        $this->ncecho("{$indent}{$indent}pr feature start <pull request number> <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Feature start and merge pull request.。')."\n");


        $this->ncecho("{$indent}{$indent}hotfix open <release name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Hotfixes arise from the necessity to act immediately upon an undesired state of a live production version.").
        __("May be branched off from the corresponding tag on the master branch that marks the production version.")."\n");
        $this->ncecho("{$indent}{$indent}hotfix close\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Finishing a hotfix it gets merged back into develop and master. Additionally the master merge is tagged with the hotfix version.")."\n");
        $this->ncecho("{$indent}{$indent}hotfix sync\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Run git live hotfix pull and git live hotfix push in succession.')."\n");
        $this->ncecho("{$indent}{$indent}hotfix state\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Check the status of hotfix.')."\n");
        $this->ncecho("{$indent}{$indent}hotfix state-all\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Check the status of hotfix.Also display merge commit.')."\n");

        $this->ncecho("{$indent}{$indent}hotfix is\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Whether the hotfix is open, or to see what is closed.')."\n");

        $this->ncecho("{$indent}{$indent}hotfix track\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Checkout remote hotfix branch.')."\n");
        $this->ncecho("{$indent}{$indent}hotfix pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Pull upstream/hotfix and deploy/hotfix.")."\n");
        $this->ncecho("{$indent}{$indent}hotfix push\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Push upstream/hotfix and deploy/hotfix.")."\n");

        $this->ncecho("{$indent}{$indent}hotfix destroy\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Discard hotfix. However, keep working in the local repository.")."\n");
        $this->ncecho("{$indent}{$indent}hotfix destroy-clean\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Discard hotfix. Also discard work in the local repository.")."\n");

        $this->ncecho("{$indent}{$indent}release open <release name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}".__('Support preparation of a new production release/.').__("Allow for minor bug fixes and preparing meta-data for a release")."\n");
        $this->ncecho("{$indent}{$indent}release close\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Finish up a release.Merges the release branch back into 'master'.Tags the release with its name.Back-merges the release into 'develop'.Removes the release branch.")."\n");
        $this->ncecho("{$indent}{$indent}release close-force\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Finish up a release.Ignore errors.")."\n");

        $this->ncecho("{$indent}{$indent}release sync\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Run git live release pull and git live release push in succession.')."\n");
        $this->ncecho("{$indent}{$indent}release state\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Check the status of release.')."\n");
        $this->ncecho("{$indent}{$indent}release state-all\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Check the status of release.Also display merge commit.')."\n");

        $this->ncecho("{$indent}{$indent}release is\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Whether the release is open, or to see what is closed.')."\n");

        $this->ncecho("{$indent}{$indent}release pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Pull upstream/release and deploy/release.")."\n");
        $this->ncecho("{$indent}{$indent}release push\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Push upstream/release and deploy/release.")."\n");

        $this->ncecho("{$indent}{$indent}release destroy\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Discard release. However, keep working in the local repository.")."\n");
        $this->ncecho("{$indent}{$indent}release destroy-clean\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__("Discard release. Also discard work in the local repository.")."\n");


        $this->ncecho("{$indent}{$indent}pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Pull from the appropriate remote repository.')."\n");
        $this->ncecho("{$indent}{$indent}push\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Push from the appropriate remote repository.')."\n");
        $this->ncecho("{$indent}{$indent}clean\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Will reset the branch before the last commit.')."\n");
        $this->ncecho("{$indent}{$indent}update\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Update git-live.')."\n");
        $this->ncecho("{$indent}{$indent}merge develop\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Merge upstream/develop and develop.')."\n");
        $this->ncecho("{$indent}{$indent}merge master\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Merge upstream/master and master.')."\n");

        $this->ncecho("{$indent}{$indent}log develop\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('show diff upstream/develop.')."\n");
        $this->ncecho("{$indent}{$indent}log master\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('show diff upstream/master.')."\n");

        $this->ncecho("{$indent}{$indent}start\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Start Git Live Flow.')."\n");
        $this->ncecho("{$indent}{$indent}restart\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Restart Git Live Flow.')."\n");

        $this->ncecho("{$indent}{$indent}init\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Initialize Git Live Flow.')."\n");

        $this->ncecho("{$indent}{$indent}init <origin_repository> <upstream_repository> <deploy_repository> (<clone_dir>)\n");
        $this->ncecho("{$indent}{$indent}{$indent}".__('Initialize git live.')."\n");
        $this->ncecho("{$indent}{$indent}{$indent}".'origin_repository：'."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}".__('Forked remote repository.')."\n");
        $this->ncecho("{$indent}{$indent}{$indent}".'upstream_repository：'."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}".__('Original remote repository.')."\n");
        $this->ncecho("{$indent}{$indent}{$indent}".'deploy_repository：'."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}".__('Remote repository for deployment.')."\n");
        $this->ncecho("{$indent}{$indent}{$indent}".'clone_dir：'."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}".__('Path to clone.')."\n");
    }
    /* ----------------------------------------- */
}
