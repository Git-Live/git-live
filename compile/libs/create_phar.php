<?php

umask(0);
$phar = new Phar(dirname(__DIR__).'/bin/git-live.phar', 0);
$phar->setSignatureAlgorithm(Phar::SHA256);
$phar->setStub(file_get_contents(__DIR__.'/src/stub.php'));



$dir = dirname(__DIR__).'/src/';
$iterator = new RecursiveDirectoryIterator($dir);
$iterator = new HtmlFilterIterator($iterator);
$iterator = new RecursiveIteratorIterator($iterator);

$list = array();
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isFile()) {
        $list[] = $fileinfo->getPathname();
    }
}





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


