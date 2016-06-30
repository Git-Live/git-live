<?php
/**
 * テストのベースクラス
 *
 *
 * PHP versions 5
 *
 *
 *
 * @category   %%project_category%%
 * @package    %%project_name%%
 * @subpackage %%subpackage_name%%
 * @author     %%your_name%% <%%your_email%%>
 * @copyright  %%your_project%%
 * @license    %%your_license%%
 * @version    GIT: $Id$
 * @link       %%your_link%%
 * @see        http://www.enviphp.net/c/man/v3/core/unittest
 * @since      File available since Release 1.0.0
 * @doc_ignore
 */
ini_set('display_errors', 1);
date_default_timezone_set('Europe/London');


define('BASE_DIR', dirname(__DIR__));

define('GIT_LIVE_INSTALL_DIR', BASE_DIR.'/.install_file');

if (!function_exists('_')) {
    function _($str)
    {
        return $str;
    }
}


if (!class_exists('\GitLive\Autoloader', false)) {
    include BASE_DIR.'/src/libs/GitLive/Autoloader.php';
}

$Autoloader = new \GitLive\Autoloader;
$Autoloader->register();
$Autoloader->addNamespace('GitLive\Compile\Compiler', BASE_DIR.'/compile/libs/Compiler');
$Autoloader->addNamespace('GitLive\Compile\Iterator', BASE_DIR.'/compile/libs/Iterator');
$Autoloader->addNamespace('GitLive\Compile\Exception', BASE_DIR.'/compile/libs/Exception');
$Autoloader->addNamespace('GitLive\Mock', BASE_DIR.'/test/Mock/GitLive');
$Autoloader->addNamespace('GitLive\Mock\Driver', BASE_DIR.'/test/Mock/GitLive/Driver');
$Autoloader->addNamespace('GitLive\Driver', __DIR__.'/libs/GitLive/Driver');
$Autoloader->addNamespace('GitLive', BASE_DIR.'/src/libs/GitLive');


/**
 * テストのベースクラス
 *
 *
 *
 * @category   %%project_category%%
 * @package    %%project_name%%
 * @subpackage %%subpackage_name%%
 * @author     %%your_name%% <%%your_email%%>
 * @copyright  %%your_project%%
 * @license    %%your_license%%
 * @version    GIT: $Id$
 * @link       %%your_link%%
 * @see        http://www.enviphp.net/c/man/v3/core/unittest
 * @since      File available since Release 1.0.0
 * @doc_ignore
 */
class testCaseBase extends EnviTestCase
{

    /**
     * +-- コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /* ----------------------------------------- */

    /**
     * +-- 初期化
     *
     * @access public
     * @return void
     */
    public function initialize()
    {
    }
    /* ----------------------------------------- */


    /**
     * +-- 終了処理をする
     *
     * @access public
     * @return void
     */
    public function shutdown()
    {
    }
    /* ----------------------------------------- */
}
