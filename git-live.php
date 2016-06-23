#!/usr/bin/env php
<?php
define('GIT_LIVE_INSTALL_DIR', __FILE__);
define('GIT_LIVE_VERSION', 'phar');
Phar::mapPhar( 'git-live.phar' );

include 'phar://git-live.phar/libs/GitLive/Autoloader.php';
include 'phar://git-live.phar/main.php';

__HALT_COMPILER(); ?>
<                    lang/messages.pov  ²KkWv  xNð¶         libs/GitLive/Autoloader.php\  ²KkW\  
Á
¶         libs/GitLive/GitLive.phpv  ²KkWv  [)¶         libs/GitLive/GitBase.phpU3  ²KkWU3  fe)¶         libs/GitLive/GitCmdExecuter.php|  ²KkW|  7óGå¶         main.phpp  ²KkWp  L?¶      # SOME DESCRIPTIVE TITLE.
# Copyright (C) YEAR THE PACKAGE'S COPYRIGHT HOLDER
# This file is distributed under the same license as the PACKAGE package.
# FIRST AUTHOR suzunone <suzunone.eleven@gmail.com>, 2016.
#
#, fuzzy
msgid ""
msgstr ""
"Project-Id-Version: PACKAGE VERSION\n"
"Report-Msgid-Bugs-To: \n"
"POT-Creation-Date: 2016-06-22 17:57+0900\n"
"PO-Revision-Date: 2016-06-22 17:57+0900\n"
"Last-Translator: suzunone <suzunone.eleven@gmail.com>\n"
"Language-Team: suzunone <suzunone.eleven@gmail.com>\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"

#: libs/GitBase.php:107
msgid "å®å¨ã§å¹ççãªããªãã¸ããªéç¨ããµãã¼ããã¾ãã"
msgstr ""

#: libs/GitBase.php:152
msgid ""
"æ°ããªéçºç¨ãã©ã³ãã'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã®'develop'ãã©ã³ãã"
"ãã¼ã¹ã¨ãã¦ä½æããéçºç¨ãã©ã³ãã«ã¹ã¤ãããã¾ãã"
msgstr ""

#: libs/GitBase.php:154
msgid ""
"è¤æ°äººã¨åãéçºãã©ã³ãã§ä½æ¥­ããã¨ããèªåã®å¤æ´åã'upstream'(å±éãªã¢ã¼ã"
"ãµã¼ãã¼)ã«ããã·ã¥ãã¾ãã"
msgstr ""

#: libs/GitBase.php:156
msgid ""
"'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ãããèª°ããä½æããéçºç¨ãã©ã³ããåå¾ãã¾"
"ãã"
msgstr ""

#: libs/GitBase.php:158
msgid ""
"'origin'(åäººç¨ãªã¢ã¼ããµã¼ãã¼)ã«éçºãã©ã³ããpushãã¾ãã(git live pushã¨"
"åä½ã¯ä¼¼ã¦ãã¾ã)"
msgstr ""

#: libs/GitBase.php:160
msgid ""
"'origin'(åäººç¨ãªã¢ã¼ããµã¼ãã¼)ããéçºãã©ã³ããpullãã¾ãã(git live pull"
"ã¨åä½ã¯ä¼¼ã¦ãã¾ã)"
msgstr ""

#: libs/GitBase.php:162
msgid ""
"ãã¹ã¦ã®å ´æãããéçºãã©ã³ããåé¤ãã¾ãããã«ãªã¯ã¨ã¹ãããã¼ã¸ããããã¨"
"ã«å®è¡ãã¦ãã ããã"
msgstr ""

#: libs/GitBase.php:165
msgid ""
"'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ãããã«ãªã¯ã¨ã¹ãããã¦ããã³ã¼ããåå¾ãã¾"
"ãã"
msgstr ""

#: libs/GitBase.php:167
msgid "pr trackãããã«ãªã¯ã¨ã¹ãã®åå®¹ãææ°å"
msgstr ""

#: libs/GitBase.php:169
msgid "ãã«ãªã¯ã¨ã¹ãã®åå®¹ããã¼ã¸ããã"
msgstr ""

#: libs/GitBase.php:172
msgid ""
"ç·æ¥å¯¾å¿ã®ããã'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã®'master'ãã©ã³ãããhotfix"
"ãéå§ãã¾ãã"
msgstr ""

#: libs/GitBase.php:174
msgid ""
"hotfixãçµäºãã'master'ã¨'develop'ã«ã³ã¼ãããã¼ã¸ããã¿ã°ãä½æãã¾ãã"
msgstr ""

#: libs/GitBase.php:176
msgid "git live hotfix pullã¨git live hotfix pushãé£ç¶ã§å®è¡ãã¾ãã"
msgstr ""

#: libs/GitBase.php:178
msgid "hotfixã®ç¶æãç¢ºèªãã¾ãã"
msgstr ""

#: libs/GitBase.php:180
msgid "èª°ããéããhotfixãåå¾ãã¾ãã"
msgstr ""

#: libs/GitBase.php:182 libs/GitBase.php:195
msgid ""
"'deploy'(ããã­ã¤ç¨ãªã¢ã¼ããµã¼ãã¼)ã¨'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ãã"
"pullãã¾ãã"
msgstr ""

#: libs/GitBase.php:184 libs/GitBase.php:197
msgid ""
"'deploy'(ããã­ã¤ç¨ãªã¢ã¼ããµã¼ãã¼)ã¨'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã«push"
"ãã¾ãã"
msgstr ""

#: libs/GitBase.php:187
msgid "ãªãªã¼ã¹ä½æ¥­ãéå§ãããããreleaseç¨ã®ãã©ã³ããä½æãã¾ãã"
msgstr ""

#: libs/GitBase.php:189
msgid ""
"releaseãçµäºãã'master'ã¨'develop'ã«ã³ã¼ãããã¼ã¸ããã¿ã°ãä½æãã¾ãã"
msgstr ""

#: libs/GitBase.php:191
msgid "git live release pullã¨git live release pushãé£ç¶ã§å®è¡ãã¾ãã"
msgstr ""

#: libs/GitBase.php:193
msgid "releaseã®ç¶æãç¢ºèªãã¾ãã"
msgstr ""

#: libs/GitBase.php:200
msgid "é©å½ãªå ´æãããpullãã¾ãã"
msgstr ""

#: libs/GitBase.php:202
msgid "é©å½ãªå ´æã«ãpushãã¾ãã"
msgstr ""

#: libs/GitBase.php:204
msgid "git-liveã³ãã³ãã®ææ°åã"
msgstr ""

#: libs/GitBase.php:206
msgid "developããç¾å¨é¸æããã¦ãããã©ã³ãã«å¤æ´ãåãè¾¼ã¿ã¾ãã"
msgstr ""

#: libs/GitBase.php:208
msgid "masterããç¾å¨é¸æããã¦ãããã©ã³ãã«å¤æ´ãåãè¾¼ã¿ã¾ãã"
msgstr ""

#: libs/GitBase.php:211
msgid "developã¨ã®diff"
msgstr ""

#: libs/GitBase.php:213
msgid "masterã¨ã®diff"
msgstr ""

#: libs/GitBase.php:216
msgid "åæåãã¾ãã"
msgstr ""

#: libs/GitBase.php:218
msgid "ãªãã¸ããªãåæ§ç¯ãã¾ãã"
msgstr ""

#: libs/GitBase.php:221
msgid "git live ã§ç®¡çãããªãã¸ããªãå¯¾è©±å½¢å¼ã§ä½æãã¾ãã"
msgstr ""

#: libs/GitBase.php:224
msgid "git live ã§ç®¡çãããªãã¸ããªãä½æãã¾ãã"
msgstr ""

#: libs/GitBase.php:226
msgid "åäººéçºç¨ã®ãªã¢ã¼ããªãã¸ããª(origin)ã"
msgstr ""

#: libs/GitBase.php:228
msgid "originã®forkåãå±æã®ãªã¢ã¼ããªãã¸ããª(upstream)ã"
msgstr ""

#: libs/GitBase.php:230
msgid "ããã­ã¤ç¨ãªãã¸ããªã"
msgstr ""

#: libs/GitBase.php:232
msgid "cloneããã­ã¼ã«ã«ã®ãã£ã¬ã¯ããªã"
msgstr ""

#: libs/GitLive.php:403
#, php-format
msgid "git live release ãä½¿ç¨ããã«ã¯ã%s ãªãã¸ããªãè¨­å®ãã¦ä¸ããã"
msgstr ""

#: libs/GitLive.php:485
msgid "Please enter only your remote-repository."
msgstr ""

#: libs/GitLive.php:497
msgid "Please enter common remote-repository."
msgstr ""

#: libs/GitLive.php:509
msgid "Please enter deploying dedicated remote-repository."
msgstr ""

#: libs/GitLive.php:510 libs/GitLive.php:524
msgid "If you return in the blank, it becomes the default setting."
msgstr ""

#: libs/GitLive.php:523
msgid "Please enter work directory path."
msgstr ""

#: libs/GitLive.php:556
msgid "ã­ã¼ã«ã«ãã£ã¬ã¯ããªãåååå¾ã§ãã¾ããã§ããã"
msgstr ""

#: libs/GitLive.php:583
msgid "git repositoryã§ã¯ããã¾ããã"
msgstr ""

#: libs/GitLive.php:927 libs/GitLive.php:934 libs/GitLive.php:956
msgid "closeã«å¤±æãã¾ããã"
msgstr ""

#: libs/GitLive.php:927
msgid " masterãReleaseãã©ã³ãããé²ãã§ãã¾ãã"
msgstr ""

#: libs/GitLive.php:956
msgid "developãReleaseãã©ã³ãããé²ãã§ãã¾ãã"
msgstr ""

#: libs/GitLive.php:1028 libs/GitLive.php:1165 libs/GitLive.php:1174
msgid "æ¢ã«release open ããã¦ãã¾ãã"
msgstr ""

#: libs/GitLive.php:1030 libs/GitLive.php:1037 libs/GitLive.php:1167
msgid "æ¢ã«hotfix open ããã¦ãã¾ãã"
msgstr ""

#: libs/GitLive.php:1059 libs/GitLive.php:1076 libs/GitLive.php:1112
#: libs/GitLive.php:1130 libs/GitLive.php:1148
msgid "hotfix openããã¦ãã¾ããã"
msgstr ""

#: libs/GitLive.php:1196 libs/GitLive.php:1213 libs/GitLive.php:1249
#: libs/GitLive.php:1266 libs/GitLive.php:1285
msgid "release openããã¦ãã¾ããã"
msgstr ""
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveAutoloader
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive;

/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveAutoloader
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class Autoloader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // normalize the base directory with a trailing separator
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // initialize the namespace prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // retain the base directory for the namespace prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        // the current namespace prefix
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir
                  . str_replace('\\', '/', $relative_class)
                  . '.php';

            // if the mapped file exists, require it
            if ($this->requireFile($file)) {
                // yes, we're done
                return $file;
            }
        }

        // never found it
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive;

/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class GitLive extends GitBase
{
    protected $deploy_repository_name = 'deploy';
    protected $GitCmdExecuter;

    /**
     * +-- ã³ã³ã¹ãã©ã¯ã¿
     *
     * @access      public
     * @return void
     */
    public function __construct()
    {
        $this->GitCmdExecuter = new GitCmdExecuter;
    }
    /* ----------------------------------------- */

    /**
     * +-- å¦çã®å®è¡
     *
     * @access      public
     * @return void
     */
    public function execute()
    {
        global $argv;
        if (!isset($argv[1])) {
            $this->help();

            return;
        }
        switch ($argv[1]) {
        case 'start':
            $this->start();
        break;
        case 'merge':
            $this->merge();
        break;
        case 'log':
            $this->log();
        break;
        case 'restart':
            $this->restart();
        break;
        case 'update':
            $this->update();
        break;
        case 'push':
            $this->push();
        break;
        case 'pull':
            $this->pull();
        break;
        case 'feature':
            $this->feature();
        break;
        case 'pr':
            $this->pr();
        break;
        case 'init':
            $this->init();
        break;
        case 'release':
            $this->release();
        break;
        case 'hotfix':
            $this->hotfix();
        break;
        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- mergeãå®è¡ãã
     *
     * @access      public
     * @return void
     */
    public function log()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        switch ($argv[2]) {
            case 'develop':
                $this->logDevelop();
            break;
            case 'master':
                $this->logMaster();
            break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- mergeãå®è¡ãã
     *
     * @access      public
     * @return void
     */
    public function merge()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        switch ($argv[2]) {
            case 'develop':
                $this->mergeDevelop();
            break;
            case 'master':
                $this->mergeMaster();
            break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- ãã«ãªã¯ã¨ã¹ãã®ç®¡ç
     *
     * @access      public
     * @return void
     */
    public function pr()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }

        switch ($argv[2]) {
        case 'track':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->prTrack($argv[3]);
        break;
        case 'pull':
            $this->prPull();
        break;
        case 'merge':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->prMerge($argv[3]);
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- prTrack
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @return void
     */
    public function prTrack($pull_request_number)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->GitCmdExecuter->fetchPullRequest();

        $repository = 'pullreq/'.$pull_request_number;
        $upstream_repository = 'remotes/pr/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->checkout($upstream_repository, array('-b', $repository));
    }
    /* ----------------------------------------- */

    /**
     * +-- pr pull
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @return void
     */
    public function prPull()
    {
        $branch = $this->getSelfBranch();
        if (!mb_ereg('/pullreq/([0-9]+)', $branch, $match)) {
            return;
        }
        $pull_request_number = $match[1];

        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- pr merge
     *
     * @access      public
     * @param  var_text $pull_request_number
     * @return void
     */
    public function prMerge($pull_request_number)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->GitCmdExecuter->fetchPullRequest();

        $upstream_repository = 'pull/'.$pull_request_number.'/head';
        $this->GitCmdExecuter->pull('upstream', $upstream_repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- releaseãå®è¡ãã
     *
     * @access      public
     * @return void
     */
    public function release()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->enableRelease();
        switch ($argv[2]) {
        case 'open':
            $this->releaseOpen();
        break;
        case 'close':
            $this->releaseClose();
        break;
        case 'close-force':
            $this->releaseClose(true);
        break;
        case 'sync':
            $this->releaseSync();
        break;
        case 'state':
            $this->releaseState();
        break;
        case 'pull':
            $this->releasePull();
        break;
        case 'push':
            $this->releasePush();
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixãå®è¡ãã
     *
     * @access      public
     * @return void
     */
    public function hotfix()
    {
        global $argv;
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p', 'deploy'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        $this->enableRelease();
        switch ($argv[2]) {
        case 'open':
            $this->hotfixOpen();
        break;
        case 'close':
            $this->hotfixClose();
        break;
        case 'sync':
            $this->hotfixSync();
        break;
        case 'state':
            $this->hotfixState();
        break;
        case 'pull':
            $this->hotfixPull();
        break;
        case 'push':
            $this->hotfixPush();
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- ãªãªã¼ã¹ãç©ºãã¦ãããã©ãã
     *
     * @access      public
     * @return void
     */
    public function isReleaseOpen()
    {
        try {
            $this->getReleaseRepository();
        } catch (exception $e) {
            return false;
        }

        return true;
    }
    /* ----------------------------------------- */

    /**
     * +-- ããããã£ã¯ã¹ãç©ºãã¦ãããã©ãã
     *
     * @access      public
     * @return void
     */
    public function isHotfixOpen()
    {
        try {
            $this->getHotfixRepository();
        } catch (exception $e) {
            return false;
        }

        return true;
    }
    /* ----------------------------------------- */

    /**
     * +-- releaseã³ãã³ããhotfixã³ãã³ããä½¿ç¨ã§ãããã©ãã
     *
     * @access      public
     * @return void
     */
    public function enableRelease()
    {
        $remote = $this->GitCmdExecuter->remote();
        $remote = explode("\n", $remote);
        $res =  array_search($this->deploy_repository_name, $remote) !== false;
        if ($res === false) {
            throw new exception(
            sprintf(_('git live release ãä½¿ç¨ããã«ã¯ã%s ãªãã¸ããªãè¨­å®ãã¦ä¸ããã'), $this->deploy_repository_name)
            );
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- ä½¿ç¨ãã¦ãããªãªã¼ã¹Repositoryã®åå¾
     *
     * @access      public
     * @return void
     */
    public function getReleaseRepository()
    {
        static $repo;
        if ($repo) {
            return $repo;
        }
        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        $repo = false;
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/upstream/release/')) {
                mb_ereg('remotes/upstream/(release/[^/]*$)', $value, $match);
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception ('release openããã¦ãã¾ããã');
        }

        return $repo;
    }
    /* ----------------------------------------- */

    /**
     * +-- ä½¿ç¨ãã¦ããhot fix Repositoryã®åå¾
     *
     * @access      public
     * @return void
     */
    public function getHotfixRepository()
    {
        static $repo;
        if ($repo) {
            return $repo;
        }
        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        $repo = false;
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/upstream/hotfix/')) {
                mb_ereg('remotes/upstream/(hotfix/[^/]*$)', $value, $match);
                $repo = $match[1];
                break;
            }
        }

        if (!$repo) {
            throw new exception ('release openããã¦ãã¾ããã');
        }

        return $repo;
    }
    /* ----------------------------------------- */

    /**
     * +-- åæåå¦çãã¾ã
     *
     * @param  var_text $clone_repository
     * @param  var_text $upstream_repository
     * @param  var_text $deploy_repository
     * @param  var_text $clone_dir
     * @return void
     */
    public function init()
    {
        global $argv;
        if (!isset($argv[3])) {
            while (true) {
                $this->ncecho(_("Please enter only your remote-repository.")."\n");
                $this->ncecho(":");
                $clone_repository = trim(fgets(STDIN, 1000));
                if ($clone_repository == '') {
                    $this->ncecho(":");
                    continue;
                }
                break;

            }

            while (true) {
                $this->ncecho(_("Please enter common remote-repository.")."\n");
                $this->ncecho(":");
                $upstream_repository = trim(fgets(STDIN, 1000));

                if ($upstream_repository == '') {
                    $this->ncecho(":");
                    continue;
                }
                break;
            }

            while (true) {
                $this->ncecho(_("Please enter deploying dedicated remote-repository.")."\n");
                $this->ncecho(_("If you return in the blank, it becomes the default setting.")."\n");
                $this->ncecho("default:{$upstream_repository}"."\n");
                $this->ncecho(":");

                $deploy_repository = trim(fgets(STDIN, 1000));

                if ($deploy_repository == '') {
                    $deploy_repository = $upstream_repository;
                }
                break;
            }

            while (true) {
                $this->ncecho(_("Please enter work directory path.")."\n");
                $this->ncecho(_("If you return in the blank, it becomes the default setting.")."\n");
                $this->ncecho("default:{$match[1]}"."\n");
                $this->ncecho(":");
                $clone_dir = trim(fgets(STDIN, 1000));

                if ($clone_dir == '') {
                    $clone_dir = NULL;
                }
                break;
            }

        } else {
            $clone_repository    = $argv[2];

            $upstream_repository = $argv[3];
            if (isset($argv[5])) {
                $deploy_repository = $argv[4];
                $clone_dir         = $argv[5];
            } elseif (!isset($argv[4])) {
                $deploy_repository = NULL;
                $clone_dir         = NULL;
            } elseif (strpos($argv[4], 'git') === 0 || strpos($argv[4], 'https:') === 0 || is_dir(realpath($argv[4]).'/.git/')) {
                $deploy_repository = $argv[4];
                $clone_dir         = NULL;
            } else {
                $clone_dir         = $argv[4];
                $deploy_repository = NULL;
            }
        }

        if ($clone_dir === NULL) {
            if (!mb_ereg('/([^/]+?)(\.git)?$', $clone_repository, $match)) {
                $this->ncecho(_('ã­ã¼ã«ã«ãã£ã¬ã¯ããªãåååå¾ã§ãã¾ããã§ããã'));
                return;
            }
            $clone_dir = getcwd().DIRECTORY_SEPARATOR.$match[1];
        }

        $this->GitCmdExecuter->copy(array('--recursive', $clone_repository, $clone_dir));

        chdir($clone_dir);
        $this->GitCmdExecuter->remote(array('add', 'upstream', $upstream_repository));

        if ($deploy_repository !== NULL) {
            $this->GitCmdExecuter->remote(array('add', 'deploy', $deploy_repository));
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- ä»ã®ãã©ã³ããåå¾ãã
     *
     * @access      public
     * @return string
     */
    public function getSelfBranch()
    {
        $self_blanch = `git symbolic-ref HEAD 2>/dev/null`;
        if (!$self_blanch) {
            throw new exception(_('git repositoryã§ã¯ããã¾ããã'));
        }

        return trim($self_blanch);
    }
    /* ----------------------------------------- */

    /**
     * +-- ããã·ã¥ãã
     *
     * @access      public
     * @return void
     */
    public function push()
    {
        $branch = $this->getSelfBranch();
        $remote = 'origin';

        if (strpos($branch, 'refs/heads/release') || strpos($branch, 'refs/heads/hotfix')) {
            $remote = 'upstream';
        }

        $this->GitCmdExecuter->push($remote, $branch);
    }
    /* ----------------------------------------- */

    /**
     * +-- ãã«ãã
     *
     * @access      public
     * @return void
     */
    public function pull()
    {
        $branch = $this->getSelfBranch();
        $remote = 'origin';
        switch ($branch) {
        case 'refs/heads/develop':
        case 'refs/heads/master':
            $remote = 'upstream';
            break;
        default:
            if (strpos($branch, 'refs/heads/release') || strpos($branch, 'refs/heads/hotfix')) {
                $remote = 'upstream';
            }
        break;
        }
        $this->GitCmdExecuter->pull($remote, $branch);
    }
    /* ----------------------------------------- */

    /**
     * +-- è«¸ãåæåãã¾ã
     *
     * @access      public
     * @return void
     */
    public function start()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->pull('upstream', 'develop');
        $this->GitCmdExecuter->push('origin', 'develop');
        $this->GitCmdExecuter->pull('upstream', 'master');
        $this->GitCmdExecuter->push('origin', 'master');
    }
    /* ----------------------------------------- */

    /**
     * +-- è«¸ããªã»ãããã¦åæåãã¾ã
     *
     * @access      public
     * @return void
     */
    public function restart()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->checkout('temp', array('-b'));
        $this->GitCmdExecuter->branch(array('-d', 'develop'));
        $this->GitCmdExecuter->branch(array('-d', 'master'));
        $this->GitCmdExecuter->push('origin', ':develop');
        $this->GitCmdExecuter->push('origin', ':master');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout('develop', array('-b'));
        $this->GitCmdExecuter->push('origin', 'develop');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout('master', array('-b'));
        $this->GitCmdExecuter->push('origin', 'master');
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
    }
    /* ----------------------------------------- */

    /**
     * +-- developããã¼ã¸ãã
     *
     * @access      public
     * @return void
     */
    public function mergeDevelop()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->merge('upstream/develop');
    }
    /* ----------------------------------------- */

    /**
     * +-- masterããã¼ã¸ãã
     *
     * @access      public
     * @return void
     */
    public function mergeMaster()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $this->GitCmdExecuter->merge('upstream/master');
    }
    /* ----------------------------------------- */

    /**
     * +-- developã¨ã®å·®åãã¿ã
     *
     * @access      public
     * @return void
     */
    public function logDevelop()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $repository = $this->getSelfBranch();
        $this->ncecho($this->GitCmdExecuter->log('develop', $repository, '--left-right'));
    }
    /* ----------------------------------------- */

    /**
     * +-- masterã¨ã®å·®åãè¦ã
     *
     * @access      public
     * @return void
     */
    public function logMaster()
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        $this->GitCmdExecuter->fetch(array('-p'));
        $repository = $this->getSelfBranch();
        $this->ncecho($this->GitCmdExecuter->log('master', $repository, '--left-right'));
    }
    /* ----------------------------------------- */

    /**
     * +-- featureãå®è¡ãã
     *
     * @access      public
     * @return void
     */
    public function feature()
    {
        global $argv;
        $this->GitCmdExecuter->fetch(array('upstream'));
        $this->GitCmdExecuter->fetch(array('-p', 'upstream'));
        // $this->enableRelease();
        if (!isset($argv[2])) {
            $this->help();

            return;
        }
        switch ($argv[2]) {
        case 'start':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->featureStart($argv[3]);
        break;
        case 'publish':
            $this->featurePublish(isset($argv[3]) ? $argv[3] : NULL);
        break;
        case 'push':
            $this->featurePush(isset($argv[3]) ? $argv[3] : NULL);
        break;
        case 'close':
            $this->featureClose(isset($argv[3]) ? $argv[3] : NULL);
        break;
        case 'track':
            if (!isset($argv[3])) {
                $this->help();

                return;
            }
            $this->featureTrack($argv[3]);
        break;
        case 'pull':
            $this->featurePull(isset($argv[3]) ? $argv[3] : NULL);
        break;

        default:
            $this->help();
        break;
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- featureãéå§ãã
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureStart($repository)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($repository, array('-b'));
    }
    /* ----------------------------------------- */

    /**
     * +-- å±ç¨Repositoryã«featureãéä¿¡ãã
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePublish($repository = NULL)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->push('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- èªåã®ãªã¢ã¼ãRepositoryã«featureãéä¿¡ãã
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePush($repository = NULL)
    {
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->push('origin', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- å±ç¨Repositoryããä»äººã®featureãåå¾ãã
     *
     * @access      public
     * @param  var_text $repository
     * @return void
     */
    public function featureTrack($repository)
    {
        if (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $self_repository = $this->getSelfBranch();
        $this->GitCmdExecuter->pull('upstream', $repository);

        if ($self_repository !== $repository) {
            $this->GitCmdExecuter->checkout($repository);
        }
    }
    /* ----------------------------------------- */

    /**
     * +-- å±ç¨Repositoryããpullãã
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featurePull($repository = NULL)
    {
        if ($repository === NULL) {
            $repository = $this->getSelfBranch();
        } elseif (strpos($repository, 'feature/') !== 0) {
            $repository = 'feature/'.$repository;
        }
        $this->GitCmdExecuter->pull('upstream', $repository);
    }
    /* ----------------------------------------- */

    /**
     * +-- featureãéãã
     *
     * @access      public
     * @param  var_text $repository OPTIONAL:NULL
     * @return void
     */
    public function featureClose($repository = NULL)
    {
        $this->GitCmdExecuter->fetch(array('--all'));
        if ($repository === NULL) {
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

    /**
     * +-- hotfixCloseã¨releaseCloseå±éå¦ç
     *
     * @access      public
     * @param  var_text $repo
     * @param  var_text $mode
     * @param  var_text $force OPTIONAL:false
     * @return void
     */
    public function deployEnd($repo, $mode, $force = false)
    {
        global $argv;

        // ãã¹ã¿ã¼ã®ãã¼ã¸
        $this->GitCmdExecuter->checkout('deploy/master');
        $this->GitCmdExecuter->branch(array('-D', 'master'));
        $this->GitCmdExecuter->checkout('master', array('-b'));

        if ($this->getSelfBranch() !== 'refs/heads/master') {
            $this->GitCmdExecuter->checkout($repo);
            throw new exception ($mode.' '._('closeã«å¤±æãã¾ããã')."\n"._(' masterãReleaseãã©ã³ãããé²ãã§ãã¾ãã'));
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);
        $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo ,'master'));

        if (strlen($diff) !== 0) {
            throw new exception($diff."\n".$mode.' '._('closeã«å¤±æãã¾ããã'));
        }
        $this->GitCmdExecuter->push('upstream', 'master');
        $this->GitCmdExecuter->push('deploy', 'master');

        // developã®ãã¼ã¸
        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->branch(array('-D', 'develop'));
        $this->GitCmdExecuter->checkout('develop', array('-b'));

        if ($this->getSelfBranch() !== 'refs/heads/develop') {
            $this->GitCmdExecuter->checkout($repo);
            throw new exception ($mode.'closeã«å¤±æãã¾ããã');
        }

        $this->GitCmdExecuter->merge('deploy/'.$repo);

        if ($mode === 'release' && !$force) {
            $diff = $this->GitCmdExecuter->diff(array('deploy/'.$repo ,'develop'));
        }

        if (strlen($diff) !== 0) {
            throw new exception ($mode.' '._('closeã«å¤±æãã¾ããã')."\n"._('developãReleaseãã©ã³ãããé²ãã§ãã¾ãã'));
        }
        $this->GitCmdExecuter->push('upstream', 'develop');

        // Repositoryã®æé¤
        $this->GitCmdExecuter->push('deploy', ':'.$repo);
        $this->GitCmdExecuter->push('upstream', ':'.$repo);
        $this->GitCmdExecuter->branch(array('-d', $repo));

        // ã¿ã°ä»ã
        $this->GitCmdExecuter->fetch(array('upstream'));
        $this->GitCmdExecuter->checkout('upstream/master');
        if (isset($argv[3])) {
            $tag = $argv[3];
        } else {
            list(, $tag) = explode('/', $repo);
            $tag = 'r'.$tag;
        }
        $this->GitCmdExecuter->tag(array($tag));
        $this->GitCmdExecuter->tagPush('upstream');
    }
    /* ----------------------------------------- */

    /**
     * +-- Deployãã©ã³ãã«Syncãã
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     */
    public function deploySync($repo)
    {
        $this->GitCmdExecuter->checkout($repo, array('-b'));
        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->pull('upstream', $repo);
        $err =  $this->GitCmdExecuter->status(array($repo));
        if (strpos(trim($err), 'nothing to commit') === false) {
            throw new exception ($err);
        }
        $this->GitCmdExecuter->push('upstream', $repo);
        $this->GitCmdExecuter->push($this->deploy_repository_name, $repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- upstream ã« pushãã
     *
     * @access      public
     * @param  var_text $repo
     * @return void
     */
    public function deployPush($repo)
    {
        $this->GitCmdExecuter->checkout($repo);
        $this->GitCmdExecuter->pull('upstream', $repo);
        $err =  $this->GitCmdExecuter->status(array($repo));
        if (strpos($err, 'nothing to commit') === false) {
            throw new exception ($err);
        }
        $this->GitCmdExecuter->push('upstream', $repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixãéã
     *
     * @access      public
     * @return void
     */
    public function hotfixOpen()
    {
        if ($this->isReleaseOpen()) {
            throw new exception(_('æ¢ã«release open ããã¦ãã¾ãã'));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(_('æ¢ã«hotfix open ããã¦ãã¾ãã'));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/hotfix/')) {
                throw new exception(_('æ¢ã«hotfix open ããã¦ãã¾ãã')."\n".$value);
            }
        }
        $hotfix_rep = 'hotfix/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/master');
        $this->GitCmdExecuter->checkout($hotfix_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $hotfix_rep);
        $this->GitCmdExecuter->push('deploy', $hotfix_rep);
    }
    /* ----------------------------------------- */

    /**
     * +-- èª°ããéããhotfixããã©ãã¯ãã
     *
     * @access      public
     * @return void
     */
    public function hotfixTrack()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openããã¦ãã¾ããã'));
        }
        $repo = $this->getHotfixRepository();
        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- èª°ããéããhotfixããã©ãã¯ãã
     *
     * @access      public
     * @return void
     */
    public function hotfixPull()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openããã¦ãã¾ããã'));
        }
        $repo = $this->getHotfixRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixã®ç¶æãç¢ºããã
     *
     * @access      public
     * @return void
     */
    public function hotfixState()
    {
        if ($this->isHotfixOpen()) {
            $repo = $this->getHotfixRepository();
            $this->ncecho($this->GitCmdExecuter->log('master', $repo));
            $this->ncecho("hotfix is open.\n");

            return;
        }
        $this->ncecho("hotfix is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- ã³ã¼ããåç°å¢ã«åæ ãã
     *
     * @access      public
     * @return void
     */
    public function hotfixSync()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openããã¦ãã¾ããã'));
        }

        $repo = $this->getHotfixRepository();

        $this->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- ã³ã¼ããåç°å¢ã«åæ ãã
     *
     * @access      public
     * @return void
     */
    public function hotfixPush()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openããã¦ãã¾ããã'));
        }

        $repo = $this->getHotfixRepository();

        $this->deployPush($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- hotfixãéãã
     *
     * @access      public
     * @return void
     */
    public function hotfixClose()
    {
        if (!$this->isHotfixOpen()) {
            throw new exception(_('hotfix openããã¦ãã¾ããã'));
        }

        $repo = $this->getHotfixRepository();
        $this->deployEnd($repo, 'hotfix');
    }
    /* ----------------------------------------- */

    /**
     * +-- ãªãªã¼ã¹ãéã
     *
     * @access      public
     * @return void
     */
    public function releaseOpen()
    {
        if ($this->isReleaseOpen()) {
            throw new exception(_('æ¢ã«release open ããã¦ãã¾ãã'));
        } elseif ($this->isHotfixOpen()) {
            throw new exception(_('æ¢ã«hotfix open ããã¦ãã¾ãã'));
        }

        $repository = $this->GitCmdExecuter->branch(array('-a'));
        $repository = explode("\n", $repository);
        foreach ($repository as $value) {
            if (strpos($value, 'remotes/'.$this->deploy_repository_name.'/release/')) {
                throw new exception(_('æ¢ã«release open ããã¦ãã¾ãã'.$value));
            }
        }
        $release_rep = 'release/'.date('Ymdhis');

        $this->GitCmdExecuter->checkout('upstream/develop');
        $this->GitCmdExecuter->checkout($release_rep, array('-b'));

        $this->GitCmdExecuter->push('upstream', $release_rep);
        $this->GitCmdExecuter->push('deploy', $release_rep);
    }
    /* ----------------------------------------- */

    /**
     * +-- èª°ããéãããªãªã¼ã¹ããã©ãã¯ãã
     *
     * @access      public
     * @return void
     */
    public function releaseTrack()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openããã¦ãã¾ããã'));
        }
        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('deploy', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- èª°ããéãããªãªã¼ã¹ãpullãã
     *
     * @access      public
     * @return void
     */
    public function releasePull()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openããã¦ãã¾ããã'));
        }
        $repo = $this->getReleaseRepository();
        $this->GitCmdExecuter->pull('upstream', $repo);
        $this->GitCmdExecuter->checkout($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- ãªãªã¼ã¹ã®ç¶æãç¢ºããã
     *
     * @access      public
     * @return void
     */
    public function releaseState()
    {
        if ($this->isReleaseOpen()) {
            $repo = $this->getReleaseRepository();
            $this->ncecho($this->GitCmdExecuter->log('master', $repo));
            $this->ncecho("release is open.\n");

            return;
        }
        $this->ncecho("release is close.\n");
    }
    /* ----------------------------------------- */

    /**
     * +-- ã³ã¼ããåç°å¢ã«åæ ãã
     *
     * @access      public
     * @return void
     */
    public function releaseSync()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openããã¦ãã¾ããã'));
        }

        $repo = $this->getReleaseRepository();
        $this->deploySync($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- ã³ã¼ããåç°å¢ã«åæ ãã
     *
     * @access      public
     * @return void
     */
    public function releasePush()
    {
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openããã¦ãã¾ããã'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployPush($repo);
    }
    /* ----------------------------------------- */

    /**
     * +-- ãªãªã¼ã¹ãéãã
     *
     * @access      public
     * @param  boolean $force OPTIONAL:false
     * @return void
     */
    public function releaseClose($force = false)
    {
        global $argv;
        if (!$this->isReleaseOpen()) {
            throw new exception(_('release openããã¦ãã¾ããã'));
        }

        $repo = $this->getReleaseRepository();
        $this->deployEnd($repo, 'release', $force);
    }
    /* ----------------------------------------- */
}
/* ----------------------------------------- */
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive;

/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class GitBase
{
    /**
     * +--
     *
     * @access      public
     * @param       var_text $text
     * @param       var_text $color OPTIONAL:NULL
     * @return      void
     */
    public function debug($text, $color = NULL)
    {
        global $is_debug;
        if (!$is_debug) {
            return;
        }
        if ($color === NULL) {
            $this->ncecho($text);

            return;
        }
        $this->cecho($text, $color);
    }
    /* ----------------------------------------- */

    /**
     * +-- è²ã¤ãecho
     *
     * @access      public
     * @param  var_text $text
     * @param  var_text $color
     * @return void
     */
    public function cecho($text, $color)
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $this->ncecho($text);

            return;
        }
        $cmd = 'echo -e "\e[3'.$color.'m'.escapeshellarg($text).'\e[m"';
        `$cmd`;
    }
    /* ----------------------------------------- */

    /**
     * +-- è²ãªãecho
     *
     * @access      public
     * @param  var_text $text
     * @return void
     */
    public function ncecho($text)
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $text = mb_convert_encoding($text, 'SJIS-win', 'utf8');
        }
        echo $text;
    }
    /* ----------------------------------------- */

    /**
     * +-- ã³ãã³ãã®ã¢ãããã¼ã
     *
     * @access      public
     * @return void
     */
    public function update()
    {
        $url = 'https://raw.githubusercontent.com/Git-Live/git-live/master/git-live.php';
        file_put_contents(__FILE__, file_get_contents($url));
    }
    /* ----------------------------------------- */

    /**
     * +-- ãã«ãã®è¡¨ç¤º
     *
     * @access      public
     * @return void
     */
    public function help()
    {
        $indent = '    ';
        $this->ncecho("GIT-LIVE(1){$indent}{$indent}{$indent}{$indent}{$indent}Git Manual{$indent}{$indent}{$indent}{$indent}{$indent}GIT-LIVE(1)\n");
        $this->ncecho("NAME\n");
        $this->ncecho("{$indent}{$indent}git-live - "._("å®å¨ã§å¹ççãªããªãã¸ããªéç¨ããµãã¼ããã¾ãã")."\n");
        $this->ncecho("SYNOPSIS\n");
        $this->ncecho("{$indent}{$indent}git live feature start <feature name>\n");
        $this->ncecho("{$indent}{$indent}git live feature publish\n");
        $this->ncecho("{$indent}{$indent}git live feature track\n");
        $this->ncecho("{$indent}{$indent}git live feature push\n");
        $this->ncecho("{$indent}{$indent}git live feature pull\n");
        $this->ncecho("{$indent}{$indent}git live feature close\n");

        $this->ncecho("{$indent}{$indent}git live pr track\n");
        $this->ncecho("{$indent}{$indent}git live pr pull\n");
        $this->ncecho("{$indent}{$indent}git live pr merge\n");

        $this->ncecho("{$indent}{$indent}git live hotfix open <release name>\n");
        $this->ncecho("{$indent}{$indent}git live hotfix close\n");
        $this->ncecho("{$indent}{$indent}git live hotfix sync\n");
        $this->ncecho("{$indent}{$indent}git live hotfix state\n");
        $this->ncecho("{$indent}{$indent}git live hotfix track\n");
        $this->ncecho("{$indent}{$indent}git live hotfix pull\n");
        $this->ncecho("{$indent}{$indent}git live hotfix push\n");

        $this->ncecho("{$indent}{$indent}git live release open <release name>\n");
        $this->ncecho("{$indent}{$indent}git live release close\n");
        $this->ncecho("{$indent}{$indent}git live release sync\n");
        $this->ncecho("{$indent}{$indent}git live release state\n");
        $this->ncecho("{$indent}{$indent}git live release track\n");
        $this->ncecho("{$indent}{$indent}git live release pull\n");
        $this->ncecho("{$indent}{$indent}git live release push\n");

        $this->ncecho("{$indent}{$indent}git live pull\n");
        $this->ncecho("{$indent}{$indent}git live push\n");
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
        $this->ncecho("{$indent}{$indent}{$indent}"._("æ°ããªéçºç¨ãã©ã³ãã'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã®'develop'ãã©ã³ãããã¼ã¹ã¨ãã¦ä½æããéçºç¨ãã©ã³ãã«ã¹ã¤ãããã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}feature publish\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("è¤æ°äººã¨åãéçºãã©ã³ãã§ä½æ¥­ããã¨ããèªåã®å¤æ´åã'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã«ããã·ã¥ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}feature track <feature name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ãããèª°ããä½æããéçºç¨ãã©ã³ããåå¾ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}feature push\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'origin'(åäººç¨ãªã¢ã¼ããµã¼ãã¼)ã«éçºãã©ã³ããpushãã¾ãã(git live pushã¨åä½ã¯ä¼¼ã¦ãã¾ã)")."\n");
        $this->ncecho("{$indent}{$indent}feature pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'origin'(åäººç¨ãªã¢ã¼ããµã¼ãã¼)ããéçºãã©ã³ããpullãã¾ãã(git live pullã¨åä½ã¯ä¼¼ã¦ãã¾ã)")."\n");
        $this->ncecho("{$indent}{$indent}feature close\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("ãã¹ã¦ã®å ´æãããéçºãã©ã³ããåé¤ãã¾ãããã«ãªã¯ã¨ã¹ãããã¼ã¸ããããã¨ã«å®è¡ãã¦ãã ããã")."\n");

        $this->ncecho("{$indent}{$indent}pr track <pull request number>\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ãããã«ãªã¯ã¨ã¹ãããã¦ããã³ã¼ããåå¾ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}pr pull \n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("pr trackãããã«ãªã¯ã¨ã¹ãã®åå®¹ãææ°å")."\n");
        $this->ncecho("{$indent}{$indent}pr merge <pull request number>\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("ãã«ãªã¯ã¨ã¹ãã®åå®¹ããã¼ã¸ããã")."\n");

        $this->ncecho("{$indent}{$indent}hotfix open <release name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("ç·æ¥å¯¾å¿ã®ããã'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã®'master'ãã©ã³ãããhotfixãéå§ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}hotfix close\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("hotfixãçµäºãã'master'ã¨'develop'ã«ã³ã¼ãããã¼ã¸ããã¿ã°ãä½æãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}hotfix sync\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("git live hotfix pullã¨git live hotfix pushãé£ç¶ã§å®è¡ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}hotfix state\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("hotfixã®ç¶æãç¢ºèªãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}hotfix track\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("èª°ããéããhotfixãåå¾ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}hotfix pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'deploy'(ããã­ã¤ç¨ãªã¢ã¼ããµã¼ãã¼)ã¨'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ããpullãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}hotfix push\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'deploy'(ããã­ã¤ç¨ãªã¢ã¼ããµã¼ãã¼)ã¨'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã«pushãã¾ãã")."\n");

        $this->ncecho("{$indent}{$indent}release open <release name>\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}"._("ãªãªã¼ã¹ä½æ¥­ãéå§ãããããreleaseç¨ã®ãã©ã³ããä½æãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}release close\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("releaseãçµäºãã'master'ã¨'develop'ã«ã³ã¼ãããã¼ã¸ããã¿ã°ãä½æãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}release sync\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("git live release pullã¨git live release pushãé£ç¶ã§å®è¡ãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}release state\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("releaseã®ç¶æãç¢ºèªãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}release pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'deploy'(ããã­ã¤ç¨ãªã¢ã¼ããµã¼ãã¼)ã¨'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ããpullãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}release push\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("'deploy'(ããã­ã¤ç¨ãªã¢ã¼ããµã¼ãã¼)ã¨'upstream'(å±éãªã¢ã¼ããµã¼ãã¼)ã«pushãã¾ãã")."\n");

        $this->ncecho("{$indent}{$indent}pull\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("é©å½ãªå ´æãããpullãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}push\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("é©å½ãªå ´æã«ãpushãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}update\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("git-liveã³ãã³ãã®ææ°åã")."\n");
        $this->ncecho("{$indent}{$indent}merge develop\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("developããç¾å¨é¸æããã¦ãããã©ã³ãã«å¤æ´ãåãè¾¼ã¿ã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}merge master\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("masterããç¾å¨é¸æããã¦ãããã©ã³ãã«å¤æ´ãåãè¾¼ã¿ã¾ãã")."\n");

        $this->ncecho("{$indent}{$indent}log develop\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("developã¨ã®diff")."\n");
        $this->ncecho("{$indent}{$indent}log master\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("masterã¨ã®diff")."\n");

        $this->ncecho("{$indent}{$indent}start\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("åæåãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}restart\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("ãªãã¸ããªãåæ§ç¯ãã¾ãã")."\n");

        $this->ncecho("{$indent}{$indent}init\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("git live ã§ç®¡çãããªãã¸ããªãå¯¾è©±å½¢å¼ã§ä½æãã¾ãã")."\n");

        $this->ncecho("{$indent}{$indent}init <clone_repository> <upstream_repository> <deploy_repository> (<clone_dir>)\n");
        $this->ncecho("{$indent}{$indent}{$indent}"._("git live ã§ç®¡çãããªãã¸ããªãä½æãã¾ãã")."\n");
        $this->ncecho("{$indent}{$indent}{$indent}"."clone_repositoryï¼"."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}"._("åäººéçºç¨ã®ãªã¢ã¼ããªãã¸ããª(origin)ã")."\n");
        $this->ncecho("{$indent}{$indent}{$indent}"."upstream_repositoryï¼"."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}"._("originã®forkåãå±æã®ãªã¢ã¼ããªãã¸ããª(upstream)ã")."\n");
        $this->ncecho("{$indent}{$indent}{$indent}"."deploy_repositoryï¼"."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}"._("ããã­ã¤ç¨ãªãã¸ããªã")."\n");
        $this->ncecho("{$indent}{$indent}{$indent}"."clone_dirï¼"."\n");
        $this->ncecho("{$indent}{$indent}{$indent}{$indent}"._("cloneããã­ã¼ã«ã«ã®ãã£ã¬ã¯ããªã")."\n");
    }
    /* ----------------------------------------- */

}
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

namespace GitLive;

/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */
class GitCmdExecuter extends GitBase
{
    /**
     * +--
     *
     * @access      public
     * @return string
     */
    public function fetchPullRequest()
    {
        $cmd = "git fetch upstream '+refs/pull/*:refs/remotes/pr/*'";
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    /* ----------------------------------------- */

    public function tag(array $options = NULL)
    {
        $cmd = 'git tag ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function copy(array $options = NULL)
    {
        $cmd = 'git clone ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function remote(array $options = NULL)
    {
        $cmd = 'git remote ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function status(array $options = NULL)
    {
        $cmd = 'git status ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function diff(array $options = NULL)
    {
        $cmd = 'git diff ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function merge($branch, array $options = NULL)
    {
        $cmd = 'git merge ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $cmd .= ' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function fetch(array $options = NULL)
    {
        $cmd = 'git fetch ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function checkout($branch, array $options = NULL)
    {
        $cmd = 'git checkout ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $cmd .= ' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function branch(array $options = NULL)
    {
        $cmd = 'git branch ';
        if (count($options)) {
            foreach ($options as $option) {
                $cmd .= ' '.$option;
            }
        }
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function pull($remote, $branch = '')
    {
        $cmd = 'git pull ';

        $cmd .= ' '.$remote.' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function push($remote, $branch = '')
    {
        $cmd = 'git push ';

        $cmd .= ' '.$remote.' '.$branch;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }
    public function tagPush($remote)
    {
        $cmd = 'git push ';

        $cmd .= ' '.$remote.' --tags';
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

    public function log($left, $right, $option = '')
    {
        $cmd = 'git log --pretty=fuller --name-status '
            .$option.' '.$left.'..'.$right;
        $this->debug($cmd, 6);
        $res = `$cmd`;
        $this->debug($res);

        return $res;
    }

}
<?php
/**
 * @category   GitCommand
 * @package    GitLive
 * @subpackage GitLiveFlow
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright Project Git Live
 * @license MIT
 * @version    GIT: $Id$
 * @link https://github.com/Git-Live/git-live
 * @see https://github.com/Git-Live/git-live
 * @since      Class available since Release 1.0.0
 */

ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

$is_debug = true;

if (!defined('GIT_LIVE_INSTALL_DIR')) {
    define('GIT_LIVE_INSTALL_DIR', __FILE__);
}

if (!defined('GIT_LIVE_VERSION')) {
    define('GIT_LIVE_VERSION', 'cli');
}
if (!class_exists('\GitLive\Autoloader', false)) {
    include 'libs/GitLive/Autoloader.php';
}


$Autoloader = new \GitLive\Autoloader;
$Autoloader->register();


if (GIT_LIVE_VERSION === 'phar') {
    $Autoloader->addNamespace('GitLive', 'phar://git-live.phar/libs/GitLive');
} else {
    $Autoloader->addNamespace('GitLive', __DIR__.'/libs/GitLive');
}

// LANG
$lang = trim(`echo \$LANG`);
if (empty($lang)) {
    $lang = 'ja_JP.UTF-8';
}


try {
    if (DIRECTORY_SEPARATOR === '\\') {
        mb_internal_encoding('utf8');
        mb_http_output('sjis-win');
        mb_http_input('sjis-win');
    }
    $GitLive = new GitLive\GitLive;
    $GitLive->execute();
} catch (exception $e) {
    $this->ncecho($e->getMessage()."\n");
}
õ:Ò»ëýÕQ+/åµXÛØ³E=DÜÑøZ©C'   GBMB