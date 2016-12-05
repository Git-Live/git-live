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
class Feature extends DriverBase
{

    /**
     * +-- featureを実行する
     *
     * @access      public
     * @return void
     */
    public function feature()
    {
        $argv = $this->getArgv();

        if (!isset($argv[2])) {
            $this->featureList();

            return;
        }


        switch ($argv[2]) {
        case 'start':
            if (!isset($argv[3])) {
                $this->Driver('Help')->help();

                return;
            }

            $this->featureStart($argv[3]);
        break;
        case 'publish':
            $this->featurePublish(isset($argv[3]) ? $argv[3] : null);
        break;
        case 'push':
            $this->featurePush(isset($argv[3]) ? $argv[3] : null);
        break;
        case 'close':
            $this->featureClose(isset($argv[3]) ? $argv[3] : null);
        break;
        case 'track':
            if (!isset($argv[3])) {
                $this->Driver('Help')->help();

                return;
            }

            $this->featureTrack($argv[3]);
        break;
        case 'pull':
            $this->featurePull(isset($argv[3]) ? $argv[3] : null);
        break;

        case 'list':
            $this->featureList();
        break;

        case 'checkout':
        case 'change':
            $this->featureChange(isset($argv[3]) ? $argv[3] : null);
        break;

        default:
            $this->Driver('Help')->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- featureの一覧を取得する
     *
     *
     * @access      public
     * @return void
     */
    public function featureList()
    {
        $this->GitCmdExecuter->branch(array('--list', '"feature/*"'));
    }
    /* ----------------------------------------- */


    /**
     * +-- featureを開始する
     *
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureStart($repository)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($repository, array('-b'));
    }
    /* ----------------------------------------- */

    /**
     * +-- featureを開始する
     *
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureChange($repository)
    {
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->checkout($repository);
    }
    /* ----------------------------------------- */


    /**
     * +-- 共用Repositoryにfeatureを送信する
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePublish($repository = null)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        if ($repository === null) {
            $repository = $this->getSelfBranchRef();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->push('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- 自分のリモートRepositoryにfeatureを送信する
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePush($repository = null)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        if ($repository === null) {
            $repository = $this->getSelfBranchRef();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->push('origin', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- 共用Repositoryから他人のfeatureを取得する
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureTrack($repository)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        $self_repository = $this->getSelfBranchRef();
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        if ($self_repository !== $repository) {
            $this->GitCmdExecuter->checkout('upstream/'.$repository);
            $this->GitCmdExecuter->checkout($repository, array('-b'));
        }

        $this->GitCmdExecuter->pull('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- 共用Repositoryからpullする
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePull($repository = null)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        if ($repository === null) {
            $repository = $this->getSelfBranchRef();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->pull('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- featureを閉じる
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featureClose($repository = null)
    {
        $this->Driver('Fetch')->all();
        $this->Driver('Fetch')->upstream();
        if ($repository === null) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }

        $this->GitCmdExecuter->push('upstream', ':'.$repository);
        $this->GitCmdExecuter->push('origin', ':'.$repository);
        $this->GitCmdExecuter->checkout('develop');
        $this->GitCmdExecuter->branch(array('-D', $repository));
    }
    /* ----------------------------------------- */

}
