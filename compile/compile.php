#!/usr/bin/env php
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveCompile
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

umask(0);

ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

define('BASE_DIR', dirname(__DIR__));

include BASE_DIR.'/src/libs/GitLive/Autoloader.php';



$Autoloader = new \GitLive\Autoloader;
$Autoloader->register();
$Autoloader->addNamespace('GitLive\Compile\Compiler', __DIR__.'/libs/Compiler');
$Autoloader->addNamespace('GitLive\Compile\Iterator', __DIR__.'/libs/Iterator');
$Autoloader->addNamespace('GitLive\Compile\Exception', __DIR__.'/libs/Exception');



try {
    if (isset($argv[1]) && $argv[1] === 'create_phar') {
        $CreateMO = new \GitLive\Compile\Compiler\CreateMO;
        $CreateMO->execute();

        $CreatePhar = new \GitLive\Compile\Compiler\CreatePhar;
        $CreatePhar->execute();
    }

    $compile_path = BASE_DIR."/bin/git-live.phar";
    if (is_file($compile_path)) {
        unlink($compile_path);
    }

    $install_path = BASE_DIR."/git-live.php";
    if (is_file($install_path)) {
        unlink($install_path);
    }

    $cmd = 'php -d phar.readonly=0 '.__FILE__.' create_phar';

    echo `$cmd`;
    chmod($compile_path, 0777);
    copy($compile_path, $install_path);
} catch (exception $e) {
}
