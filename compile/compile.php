#!/usr/bin/env php
<?php

if (isset($argv[1]) && $argv[1] === 'create_phar') {
    umask(0);
    $phar = new Phar(dirname(__DIR__).'/bin/git-live.phar', 0);
    $phar->setSignatureAlgorithm(Phar::SHA256);
    $phar->setStub("#!/usr/bin/env php
<?php
    define('GIT_LIVE_INSTALL_DIR', __FILE__);
    define('GIT_LIVE_VERSION', 'phar');
    Phar::mapPhar( 'git-live.phar' );

    include_once 'phar://git-live.phar/git-live.php';
    __HALT_COMPILER(); ?>
    ");

    $phar->addFile(dirname(__DIR__).'/src/lang/messages.po', 'lang/messages.po');
    $phar->addFile(dirname(__DIR__).'/src/libs/GitBase.php', 'libs/GitBase.php');
    $phar->addFile(dirname(__DIR__).'/src/libs/GitCmdExecuter.php', 'libs/GitCmdExecuter.php');
    $phar->addFile(dirname(__DIR__).'/src/libs/GitLive.php', 'libs/GitLive.php');
    $phar->addFile(dirname(__DIR__).'/src/git-live.php', 'git-live.php');





    // $phar->addFile('TestClass2.php');
    // $phar->addFile('TestClass3.php', 'subdir/filename.php'); //別名で保存
    // $phar['TestClass4.php'] = file_get_contents('TestClass4.php'); //配列形式でも保存可能
    // $phar->addFile('mushroom.gif');
    $phar->stopBuffering();
    die;

}

$compile_path = dirname(__DIR__)."/bin/git-live.phar";
if (is_file($compile_path)) {
    unlink($compile_path);
}

$install_path = dirname(__DIR__)."/git-live.php";
if (is_file($install_path)) {
    unlink($install_path);
}

$cmd = 'php -d phar.readonly=0 '.__DIR__.'/compile.php create_phar';

echo `$cmd`;
chmod($compile_path, 0777);
copy($compile_path, $install_path);
