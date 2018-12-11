<?php return array (
  'domain' => NULL,
  'plural-forms' => NULL,
  'messages' => 
  array (
    '' => 
    array (
      '' => 
      array (
        0 => 'Project-Id-Version: PACKAGE VERSION
Report-Msgid-Bugs-To: 
Last-Translator: FULL NAME <EMAIL@ADDRESS>
Language-Team: LANGUAGE <LL@li.org>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
POT-Creation-Date: 2017-04-09 10:36+0900
PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE
Language: 
',
      ),
      'Alert: An update to the Git Live is available. Run "git live update" to get the latest version.' => 
      array (
        0 => 'Alert: "git live update" を実行して、最新版にアップデートしてください。',
      ),
      'Add a remote repository %s.' => 
      array (
        0 => 'リモートリポジトリ%sを追加してください。',
      ),
      'Not a git repository.' => 
      array (
        0 => 'Gitリモートリポジトリではありません。',
      ),
      '%s close is failed.' => 
      array (
        0 => '%s closeに失敗しました。',
      ),
      '%s branch has a commit that is not on the %2$s branch' => 
      array (
        0 => '%sブランチに%2$sより新しいコミットが存在します。',
      ),
      'Already %s opened.' => 
      array (
        0 => '既に%s openされています。',
      ),
      '%s is not open.' => 
      array (
        0 => '%s openされていません。',
      ),
      '%s is open.' => 
      array (
        0 => '',
      ),
      '%s is close.' => 
      array (
        0 => '',
      ),
      'Please enter only your remote-repository.' => 
      array (
        0 => 'フォークしたあなた専用のリモートリポジトリを入力してください。',
      ),
      'Please enter common remote-repository.' => 
      array (
        0 => 'フォーク元の共有リモートリポジトリを入力してください。',
      ),
      'Please enter deploying dedicated remote-repository.' => 
      array (
        0 => 'デプロイ用のリモートリポジトリを入力してください。',
      ),
      'If you return in the blank, it becomes the default setting.' => 
      array (
        0 => '空白のままにすると、デフォルトの設定が使用されます。',
      ),
      'Please enter work directory path.' => 
      array (
        0 => 'cloneするワークディレクトリを入力してください。',
      ),
      'Could not automatically get the local directory.' => 
      array (
        0 => 'ローカルディレクトリの自動取得に失敗しました。',
      ),
      'feature branch create fail.' => 
      array (
        0 => 'featureブランチの作成に失敗しました。',
      ),
      'Supports safe and efficient repository operation.' => 
      array (
        0 => '安全で効率的な、リポジトリ運用をサポートします。',
      ),
      'Create a new feature branch.(From upstream/develop)' => 
      array (
        0 => '新たな開発用ブランチを\'upstream\'(共通リモートサーバー)の\'develop\'ブランチをベースとして作成し、開発用ブランチにスイッチします。',
      ),
      'Checkout other feature branch.' => 
      array (
        0 => '作成済の別featureに移動します。',
      ),
      'Alias of "git live feature checkout".' => 
      array (
        0 => 'git live feature checkoutへのエイリアスです。',
      ),
      'Show feature list.' => 
      array (
        0 => 'featureの一覧を取得します。',
      ),
      'Safe push to upstream repository.' => 
      array (
        0 => '複数人と同じ開発ブランチで作業するとき、自分の変更分を\'upstream\'(共通リモートサーバー)にプッシュします。',
      ),
      'Safe checkout feature branch from upstream repository.' => 
      array (
        0 => '\'upstream\'(共通リモートサーバー)から、誰かが作成した開発用ブランチを取得します。',
      ),
      'Safe push to origin repository.' => 
      array (
        0 => '\'origin\'(個人用リモートサーバー)にFeatureブランチをpushします。(git live pushと動作は似ています)',
      ),
      'Safe pull to upstream repository.' => 
      array (
        0 => '\'upstream\'(共有リモートサーバー)からFeatureブランチをpullします。(git live feature trackと動作は似ていますが、trackは新規にブランチを作成するところが異なります)',
      ),
      'Remove feature branch, from all repository.' => 
      array (
        0 => 'すべての場所から、開発ブランチを削除します。プルリクエストがマージされたあとに実行してください。',
      ),
      '\'Checkout pull request locally.' => 
      array (
        0 => '\'upstream\'(共通リモートサーバー)からプルリクエストされているコードを取得します。',
      ),
      'Pull pull request locally.' => 
      array (
        0 => 'pr trackしたプルリクエストの内容を最新化',
      ),
      'Merge pull request locally.' => 
      array (
        0 => 'プルリクエストの内容をマージする。',
      ),
      'Feature start from pull request.' => 
      array (
        0 => 'プルリクエストから新たなfeatureを作成します。',
      ),
      'Feature start and merge pull request.。' => 
      array (
        0 => 'プルリクエストをマージした新しいfeatureを作成する。',
      ),
      'Hotfixes arise from the necessity to act immediately upon an undesired state of a live production version.' => 
      array (
        0 => '緊急対応のためのフローを開始します。',
      ),
      'May be branched off from the corresponding tag on the master branch that marks the production version.' => 
      array (
        0 => 'マスターから緊急対応用のブランチを作成します。',
      ),
      'Finishing a hotfix it gets merged back into develop and master. Additionally the master merge is tagged with the hotfix version.' => 
      array (
        0 => 'masterとdevelopにマージしてタグを作成し、緊急対応を修正します。',
      ),
      'Run git live hotfix pull and git live hotfix push in succession.' => 
      array (
        0 => 'git live hotfix pullとgit live hotfix pushを連続で実行します。',
      ),
      'Check the status of hotfix.' => 
      array (
        0 => 'ホットフィクスの状態を確認します。',
      ),
      'Check the status of hotfix.Also display merge commit.' => 
      array (
        0 => 'ホットフィクスの状態を確認します。表示する差分には、マージコミットを含めます。',
      ),
      'Whether the hotfix is open, or to see what is closed.' => 
      array (
        0 => 'ホットフィクスが開いているか、閉じているかを確認する。',
      ),
      'Checkout remote hotfix branch.' => 
      array (
        0 => '誰かが開けたhotfixを取得します。',
      ),
      'Pull upstream/hotfix and deploy/hotfix.' => 
      array (
        0 => '\'deploy\'(デプロイ用リモートサーバー)と\'upstream\'(共通リモートサーバー)からpullします。',
      ),
      'Push upstream/hotfix and deploy/hotfix.' => 
      array (
        0 => '\'deploy\'(デプロイ用リモートサーバー)と\'upstream\'(共通リモートサーバー)からpullします。',
      ),
      'Discard hotfix. However, keep working in the local repository.' => 
      array (
        0 => 'ホットフィクスの破棄。ローカルの作業ブランチは残す。',
      ),
      'Discard hotfix. Also discard work in the local repository.' => 
      array (
        0 => 'ホットフィクスの破棄。すべてを破棄し、ローカルでの作業も破棄する。',
      ),
      'Support preparation of a new production release/.' => 
      array (
        0 => '通常リリースのためのフローを開始します。',
      ),
      'Allow for minor bug fixes and preparing meta-data for a release' => 
      array (
        0 => '簡単なバグフィクスも、release open後に行うことができます。',
      ),
      'Finish up a release.Merges the release branch back into \'master\'.Tags the release with its name.Back-merges the release into \'develop\'.Removes the release branch.' => 
      array (
        0 => '\'master\'と\'develop\'にコードをマージ、タグを作成し、releaseを終了します。',
      ),
      'Finish up a release.Ignore errors.' => 
      array (
        0 => 'developとの差分を確認せずマージします。',
      ),
      'Run git live release pull and git live release push in succession.' => 
      array (
        0 => 'git live release pullとgit live release pushを連続で実行します。',
      ),
      'Check the status of release.' => 
      array (
        0 => 'releaseの状態を確認します。',
      ),
      'Check the status of release.Also display merge commit.' => 
      array (
        0 => 'リリースの状態を確認します。表示する差分には、マージコミットを含めます。',
      ),
      'Whether the release is open, or to see what is closed.' => 
      array (
        0 => 'リリースが開いているか、閉じているかを確認する。',
      ),
      'Pull upstream/release and deploy/release.' => 
      array (
        0 => 'リリース用のリモートリポジトリ(upstream/release と deploy/release)からpullします。',
      ),
      'Push upstream/release and deploy/release.' => 
      array (
        0 => 'リリース用のリモートリポジトリ(upstream/release と deploy/release)にpushします。',
      ),
      'Discard release. However, keep working in the local repository.' => 
      array (
        0 => 'リリースの破棄。ローカルの作業ブランチは残す。',
      ),
      'Discard release. Also discard work in the local repository.' => 
      array (
        0 => 'リリースの破棄。すべてを破棄し、ローカルでの作業も破棄する。',
      ),
      'Pull from the appropriate remote repository.' => 
      array (
        0 => '適当なリモートリポジトリからpullします。',
      ),
      'Push from the appropriate remote repository.' => 
      array (
        0 => '適当なリモートリポジトリにpushします。',
      ),
      'Will reset the branch before the last commit.' => 
      array (
        0 => 'ブランチを最後のコミットの状態にします。',
      ),
      'Update git-live.' => 
      array (
        0 => 'git-liveコマンドを更新します。',
      ),
      'Merge upstream/develop and develop.' => 
      array (
        0 => 'developをマージします。',
      ),
      'Merge upstream/master and master.' => 
      array (
        0 => 'masterをマージします。',
      ),
      'show diff upstream/develop.' => 
      array (
        0 => 'developとのdiffを表示します。',
      ),
      'show diff upstream/master.' => 
      array (
        0 => 'masterとのdiffを表示します。',
      ),
      'Start Git Live Flow.' => 
      array (
        0 => '初期化します。',
      ),
      'Restart Git Live Flow.' => 
      array (
        0 => 'リポジトリを再構築します。',
      ),
      'Initialize Git Live Flow.' => 
      array (
        0 => 'git live で管理するリポジトリを対話形式、あるいはオプションを指定して作成します。',
      ),
      'Initialize git live.' => 
      array (
        0 => 'git live で管理するリポジトリを作成します。',
      ),
      'Forked remote repository.' => 
      array (
        0 => '個人開発用のリモートリポジトリ(origin)。',
      ),
      'Original remote repository.' => 
      array (
        0 => 'originのfork元、共有のリモートリポジトリ(upstream)。',
      ),
      'Remote repository for deployment.' => 
      array (
        0 => 'デプロイ用リポジトリ。',
      ),
      'Path to clone.' => 
      array (
        0 => 'cloneするローカルのディレクトリ。',
      ),
    ),
  ),
);