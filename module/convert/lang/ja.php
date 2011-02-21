<?php
/**
 * The convert module Japanese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->convert->common  = 'インポート';
$lang->convert->next    = '次の';
$lang->convert->pre     = 'バック';
$lang->convert->reload  = 'リロード';
$lang->convert->error   = 'エラー';

$lang->convert->start   = '開始のインポート';
$lang->convert->desc    = <<<EOT
<p>Welcome to use this convert wizard which will help you to import other system data to ZenTaoPMS.</p>
<strong>Importing is dangerous. Be sure to backup your database and other data files and sure nobody is using pms when importing.</strong>
EOT;

$lang->convert->selectSource     = '選択してソースシステムとバージョン';
$lang->convert->source           = 'ソースシステム';
$lang->convert->version          = 'バージョン';
$lang->convert->mustSelectSource = "システムのソースを選択してください";

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');

$lang->convert->setting     = '設定';
$lang->convert->checkConfig = '設定を確認します';

$lang->convert->ok         = 'チェック渡さ（√）';
$lang->convert->fail       = 'チェックに失敗しました（×）';

$lang->convert->settingDB  = 'Setデータベース';
$lang->convert->dbHost     = 'データベースサーバー';
$lang->convert->dbPort     = 'サーバーのポート';
$lang->convert->dbUser     = 'データベースユーザー';
$lang->convert->dbPassword = 'データベースのパスワード';
$lang->convert->dbName     = '%s database';
$lang->convert->dbCharset  = '%s データベースコード ';
$lang->convert->dbPrefix   = '%s table prefix';
$lang->convert->installPath= '%s installed path';

$lang->convert->checkDB    = 'データベース';
$lang->convert->checkTable = 'テーブル';
$lang->convert->checkPath  = 'インストールパス';

$lang->convert->execute    = '実行のインポート';
$lang->convert->item       = '輸入商品';
$lang->convert->count      = 'カウント';
$lang->convert->info       = '情報';

$lang->convert->bugfree->users    = 'ユーザー';
$lang->convert->bugfree->projects = 'プロジェクト';
$lang->convert->bugfree->modules  = 'モジュール';
$lang->convert->bugfree->bugs     = 'バグ';
$lang->convert->bugfree->cases    = 'ケース';
$lang->convert->bugfree->results  = '結果';
$lang->convert->bugfree->actions  = '歴史';
$lang->convert->bugfree->files    = 'ファイル';

$lang->convert->errorConnectDB     = 'データベースへの接続のサーバーが失敗しました。';
$lang->convert->errorFileNotExits  = 'File %s not exits.';
$lang->convert->errorUserExists    = 'User %s exits already.';
$lang->convert->errorCopyFailed    = 'file %s copy failed.';
