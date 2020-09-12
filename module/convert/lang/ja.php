<?php
/**
 * The convert module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wuhongjie wangguannan
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->convert->common = '他のシステムからインポート';
$lang->convert->index = 'ホーム';

$lang->convert->start = '転換開始';
$lang->convert->desc    = <<<EOT
<p>欢迎使用系统转换向导，本程序会帮助您将其他系统的数据转换到禅道项目管理系统中。</p>
<strong>转换存在一定的风险，转换之前，我们强烈建议您备份数据库及相应的数据文件，并保证转换的时候，没有其他人进行操作。</strong>
EOT;

$lang->convert->setConfig = 'ソースシステム構成';
$lang->convert->setBugfree = 'Bugfree配置';
$lang->convert->setRedmine = 'Redmine配置';
$lang->convert->checkBugFree = 'Bugfree検査';
$lang->convert->checkRedmine = 'Redmine検査';
$lang->convert->convertRedmine = 'Redmine転換';
$lang->convert->convertBugFree = 'BugFree転換';

$lang->convert->selectSource = 'ソースシステムとバージョンを選択';
$lang->convert->mustSelectSource = 'ソースを選択してください';

$lang->convert->direction = "{$lang->projectCommon}問題の転換方向を選択してください";
$lang->convert->questionTypeOfRedmine = 'Redmineの中の問題タイプ';
$lang->convert->aimTypeOfZentao = 'Zentaoの中のタイプに変更';

$lang->convert->directionList['bug'] = 'バグ';
$lang->convert->directionList['task'] = 'タスク';
$lang->convert->directionList['story'] = $lang->storyCommon;

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '3.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.2');

$lang->convert->setting = '設定';
$lang->convert->checkConfig = '配置検査';

$lang->convert->ok = '<span class="text-success"><i class="icon-check-sign"></i> 検査通過</span>';
$lang->convert->fail = '<span class="text-danger"><i class="icon-remove-sign"></i> 検査失敗</span>';

$lang->convert->dbHost = 'データベースサーバー';
$lang->convert->dbPort = 'サーバーポート';
$lang->convert->dbUser = 'データベースのユーザ名';
$lang->convert->dbPassword = 'データベースのパスワード';
$lang->convert->dbName = '%sが使用しているライブラリ';
$lang->convert->dbCharset = '%sデータベースのコード';
$lang->convert->dbPrefix = '%s表のプレフィックス';
$lang->convert->installPath = '%sインストールのルートディレクトリ';

$lang->convert->checkDB = 'データベース';
$lang->convert->checkTable = '表';
$lang->convert->checkPath = 'インストールパス';

$lang->convert->execute = '転換を実行';
$lang->convert->item = '項を転換';
$lang->convert->count = '転換数';
$lang->convert->info = '転換情報';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users = 'ユーザ';
$lang->convert->bugfree->projects = $lang->projectCommon;
$lang->convert->bugfree->modules = 'モジュール';
$lang->convert->bugfree->bugs = 'バグ';
$lang->convert->bugfree->cases = 'テストケース';
$lang->convert->bugfree->results = 'テスト結果';
$lang->convert->bugfree->actions = '履歴';
$lang->convert->bugfree->files = '添付ファイル';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users = 'ユーザ';
$lang->convert->redmine->groups = 'ユーザグルーピング';
$lang->convert->redmine->products = $lang->productCommon;
$lang->convert->redmine->projects = $lang->projectCommon;
$lang->convert->redmine->stories = $lang->storyCommon;
$lang->convert->redmine->tasks = 'タスク';
$lang->convert->redmine->bugs = 'バグ';
$lang->convert->redmine->productPlans = $lang->productCommon . 'プラン';
$lang->convert->redmine->teams = 'チーム';
$lang->convert->redmine->releases = 'リリース';
$lang->convert->redmine->builds = 'Build';
$lang->convert->redmine->docLibs = 'ドキュメントライブラリ';
$lang->convert->redmine->docs = 'ドキュメント';
$lang->convert->redmine->files = '添付ファイル';

$lang->convert->errorFileNotExits = 'ファイル%sは存在しません';
$lang->convert->errorUserExists = 'ユーザ％sは既に存在しています';
$lang->convert->errorGroupExists = 'グループ％sは既に存在しています';
$lang->convert->errorBuildExists = 'Build%sは既に存在しています';
$lang->convert->errorReleaseExists = 'リリース%sは既に存在しています';
$lang->convert->errorCopyFailed = 'ファイル%sのコピーに失敗しました';

$lang->convert->setParam = '変換パラメータを設定してください';

$lang->convert->statusType = new stdclass();
$lang->convert->priType = new stdclass();

$lang->convert->aimType = '問題タイプ転換';
$lang->convert->statusType->bug = 'ステータスタイプ転換(Bugステータス)';
$lang->convert->statusType->story = 'ステータスタイプ転換(Storyステータス)';
$lang->convert->statusType->task = 'ステータスタイプ転換(Taskステータス)';
$lang->convert->priType->bug = '優先度タイプ転換(Bugステータス)';
$lang->convert->priType->story = '優先度タイプ転換(Storyステータス)';
$lang->convert->priType->task = '優先度タイプ転換(Taskステータス)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao = '禅道';
$lang->convert->issue->goto = 'に変更';
