<?php
/**
 * The install module Japanese file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->install->common  = 'インストール';
$lang->install->next    = '次の';
$lang->install->pre     = 'バック';
$lang->install->reload  = 'リロード';
$lang->install->error   = 'エラー';

$lang->install->start            = 'インストール開始';
$lang->install->keepInstalling   = 'このバージョンをインストールください';
$lang->install->seeLatestRelease = '最新のリリースを参照してください。';
$lang->install->welcome          = 'ようこそZenTaoPMSを使用します。';
$lang->install->desc             = <<<EOT
ZenTaoPMS is an opensource project management software licensed under LGPL. It has product manage, project mange, testing mange features, also with organization manage and affair manage.

ZenTaoPMS is developped by PHH and mysql under the zentaophp framework developped by the same team. Through the framework, ZenTaoPMS can be customed and extended very easily.

ZenTaoPMS is developped by <strong class='red'><a href='http://www.cnezsoft.com' target='_blank'>Nature EasySoft Network Tecnology Co.ltd, QingDao, China</a></strong>。
The official website of ZenTaoPMS is <a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a>

The version of current release is <strong class='red'>%s</strong>。
EOT;


$lang->install->newReleased= "<strong class='red'>Notice</strong>：There is a new version <strong class='red'>%s</strong>, released on %s。";
$lang->install->choice     = 'することができます';
$lang->install->checking   = 'システム点検';
$lang->install->ok         = '[OK]を（√）';
$lang->install->fail       = '失敗しました（×）';
$lang->install->loaded     = '読み込まれている';
$lang->install->unloaded   = '読み込まれていない';
$lang->install->exists     = '存在する';
$lang->install->notExists  = 'まだ存在して';
$lang->install->writable   = '書き込み可能な';
$lang->install->notWritable= '書き込みしない';
$lang->install->phpINI     = 'PHPのiniファイルの';
$lang->install->checkItem  = 'アイテム';
$lang->install->current    = '現在の';
$lang->install->result     = '結果';
$lang->install->action     = 'どのように修正する';

$lang->install->phpVersion = 'PHPのバージョン';
$lang->install->phpFail    = '5.2.0を&gt;する必要があります';

$lang->install->pdo          = 'PDO拡張モジュール';
$lang->install->pdoFail      = '編集は、php.iniは、PDOのextsionを読み込むためにファイルです。';
$lang->install->pdoMySQL     = 'PDO_MYSQLは拡張';
$lang->install->pdoMySQLFail = '編集は、php.iniはPDO_MYSQLはextsionを読み込むためにファイルです。';
$lang->install->tmpRoot      = 'Tempディレクトリ';
$lang->install->dataRoot     = 'アップロードディレクトリにあります。';
$lang->install->mkdir        = '<p>Should creat the directory %s。<br /> Under linux, can try<br /> mkdir -p %s</p>';
$lang->install->chmod        = 'Should change the permission of "%s".<br />Under linux, can try<br />chmod o=rwx -R %s';

$lang->install->settingDB    = 'Setデータベース';
$lang->install->webRoot      = 'ZenTaoPMSパス';
$lang->install->requestType  = 'URLタイプ';
$lang->install->requestTypes['GET']       = 'GETを';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFOを';
$lang->install->dbHost     = 'データベースホスト';
$lang->install->dbHostNote = 'localhostは接続できる場合は、127.0.0.1を試してください';
$lang->install->dbPort     = 'ホストポート';
$lang->install->dbUser     = 'データベースユーザー';
$lang->install->dbPassword = 'データベースのパスワード';
$lang->install->dbName     = 'データベース名';
$lang->install->dbPrefix   = 'テーブル接頭語';
$lang->install->createDB   = '自動データベースを作成する';
$lang->install->clearDB    = 'クリアデータをデータベースが存在する場合。';

$lang->install->errorConnectDB     = 'データベースが失敗に接続します。';
$lang->install->errorCreateDB      = 'データベースが失敗を作成します。';
$lang->install->errorCreateTable   = '表に失敗しましたを作成します。';

$lang->install->setConfig  = '作成設定ファイル';
$lang->install->key        = '項目';
$lang->install->value      = '値';
$lang->install->saveConfig = '保存は、設定';
$lang->install->save2File  = '<div class="a-center"><span class="fail">Try to save the config auto, but failed.</span></div>Copy the text of the textareaand save to "<strong> %s </strong>".';
$lang->install->saved2File = 'The config file has saved to "<strong>%s</strong> ".';
$lang->install->errorNotSaveConfig = '还没有保存配置文件';

$lang->install->getPriv  = '設定管理';
$lang->install->company  = '会社名';
$lang->install->pms      = 'ZenTaoPMSドメイン';
$lang->install->pmsNote  = 'ドメイン名またはIP ZenTaoPMSのアドレスははhttp://';
$lang->install->account  = '管理者';
$lang->install->password = '管理者パスワード';
$lang->install->errorEmptyPassword = "空にすることはできません";

$lang->install->success = "成功は、ZenTaoPMSには、ログインしてくださいグループと権限を付与作成インストールされます。";

