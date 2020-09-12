<?php
/**
 * The install module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin wangguannan
 * @package     install
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common = 'インストール';
$lang->install->next = '次';
$lang->install->pre = '戻る';
$lang->install->reload = 'ページ更新';
$lang->install->error = 'エラー';

$lang->install->officeDomain = 'https://www.zentao.net';

$lang->install->start = 'インストール開始';
$lang->install->keepInstalling = '現在のバージョンのインストールを続行する';
$lang->install->seeLatestRelease = '最新のバージョンを見る';
$lang->install->welcome = '禅道プロジェクト管理ソフトへようこそ！';
$lang->install->license = '禅道プロジェクト管理ソフトは Z PUBLIC LICENSE(ZPL) 1.2 ライセンス契約を使用しています';
$lang->install->desc             = <<<EOT
禅道项目管理软件(ZenTaoPMS)是一款国产的，基于<a href='http://zpl.pub' target='_blank'>ZPL</a>协议，开源免费的项目管理软件，它集产品管理、项目管理、测试管理于一体，同时还包含了事务管理、组织管理等诸多功能，是中小型企业项目管理的首选。

禅道项目管理软件使用PHP + MySQL开发，基于自主的PHP开发框架──ZenTaoPHP而成。第三方开发者或者企业可以非常方便的开发插件或者进行定制。
EOT;

$lang->install->links = <<<EOT
禅道项目管理软件由<strong><a href='http://www.cnezsoft.com' target='_blank' class='text-danger'>青岛易软天创网络科技有限公司</a>开发</strong>。
官方网站：<a href='https://www.zentao.net' target='_blank'>https://www.zentao.net</a>
技术支持：<a href='https://www.zentao.net/ask/' target='_blank'>https://www.zentao.net/ask/</a>
新浪微博：<a href='http://weibo.com/easysoft' target='_blank'>http://weibo.com/easysoft</a>



您现在正在安装的版本是 <strong class='text-danger'>%s</strong>。
EOT;

$lang->install->newReleased = "<strong class='text-danger'>ヒント</strong>：公式サイトには最新バージョン<strong class='text-danger'>%s</strong>があり、リリース日付は ％ｓ であります。";
$lang->install->or = '或いは';
$lang->install->checking = 'システムチェック';
$lang->install->ok = 'チェックで通過（○）';
$lang->install->fail = 'チェックで失敗(×)';
$lang->install->loaded = 'ロード済';
$lang->install->unloaded = '未ロード';
$lang->install->exists = 'ディレクトリー存在';
$lang->install->notExists = 'ディレクトリー存在しません';
$lang->install->writable = 'ディレクトリー編集可能';
$lang->install->notWritable = 'ディレクトリー編集不可';
$lang->install->phpINI = 'PHP配置ファイル';
$lang->install->checkItem = 'チェック項目';
$lang->install->current = '現配置';
$lang->install->result = 'チェック結果';
$lang->install->action = '修正策';

$lang->install->phpVersion = 'PHPバージョン';
$lang->install->phpFail = 'PHPバージョンが5.2.0以上が必要です';

$lang->install->pdo = 'PDO拡張';
$lang->install->pdoFail = 'PHP配置ファイルを修正して、PDO拡張をロードします。';
$lang->install->pdoMySQL = 'PDO＿ＭｙＳＱＬ拡張';
$lang->install->pdoMySQLFail = 'PHP配置ファイルを修正して、pdo_mysql拡張をロードします。';
$lang->install->json = 'JSON拡張';
$lang->install->jsonFail = 'PHP配置ファイルを修正し、JSON拡張をロードします。';
$lang->install->openssl = 'OPENSSL拡張';
$lang->install->opensslFail = 'PHP配置ファイルを修正し、OPENSSL拡張をロードします。';
$lang->install->mbstring = 'MBSTRING拡張';
$lang->install->mbstringFail = 'PHP配置ファイルを修正して、MBSTRING拡張をロードします。';
$lang->install->zlib = 'ZLIB拡張';
$lang->install->zlibFail = 'PHP配置ファイルを修正し、ZLIB拡張をロードします。';
$lang->install->curl = 'CURL拡張';
$lang->install->curlFail = 'PHP配置ファイルを修正し、CURL拡張をロードします。';
$lang->install->filter = 'FILTER拡張';
$lang->install->filterFail = 'PHP配置ファイルを修正し、FILTER拡張をロードします。';
$lang->install->gd = 'GD拡張';
$lang->install->gdFail = 'PHP配置ファイルを修正し、GD拡張をロードします。';
$lang->install->iconv = 'ICONV拡張';
$lang->install->iconvFail = 'PHP配置ファイルを修正し、ICONV拡張をロードします。';
$lang->install->tmpRoot = 'テンポラリファイルディレクトリー';
$lang->install->dataRoot = 'ファイルディレクトリーアップロード';
$lang->install->session = 'Sessionストレージディレクトリー';
$lang->install->sessionFail = 'PHP配置ファイルを修正し、session.save_pathを設定します';
$lang->install->mkdirWin = '<p>ディレクトリー％ｓを作成する必要があります。コマンドは次のとおりです：<br /> mkdir %s</p>';
$lang->install->chmodWin = 'ディレクトリー "%s" の権限を変更する必要があります。';
$lang->install->mkdirLinux = '<p>ディレクトリー％ｓを作成する必要があります。<br /> コマンドは次のとおりです：<br /> mkdir -p %s</p>';
$lang->install->chmodLinux = 'ディレクトリー "%s" の権限を変更する必要があります。<br />コマンドは次のとおりです：<br />chmod o=rwx -R %s';

$lang->install->timezone = 'タイムゾーン設定';
$lang->install->defaultLang = 'デフォルト言語';
$lang->install->dbHost = 'データベースサーバ';
$lang->install->dbHostNote = '127.0.0.1にアクセスできない場合、localhostを使用してみてください。';
$lang->install->dbPort = 'サーバポート';
$lang->install->dbEncoding     = '数据库编码';
$lang->install->dbUser = 'データベースユーザ名';
$lang->install->dbPassword = 'データベースパスワード';
$lang->install->dbName = 'PMS用ライブラリ';
$lang->install->dbPrefix = '表の作成に使うプレフィックス';
$lang->install->clearDB = '既存データ削除';
$lang->install->importDemoData = 'demoデータインポート';
$lang->install->working = '仕事方式';

$lang->install->requestTypes['GET'] = '普通方式';
$lang->install->requestTypes['PATH_INFO'] = '静的友好方式';

$lang->install->workingList['full'] = '開発管理ツール';
$lang->install->workingList['onlyTest'] = 'テスト管理ツール';
$lang->install->workingList['onlyStory'] = $lang->storyCommon . '管理ツール';
$lang->install->workingList['onlyTask'] = 'タスク管理ツール';

$lang->install->errorConnectDB = 'データベースとの接続に失敗しました';
$lang->install->errorDBName = 'データベース名には “.” を含むことができません';
$lang->install->errorCreateDB = 'データベースの作成に失敗しました';
$lang->install->errorTableExists = 'データテーブルは既に存在しており、禅道をインストールしたことがありました。前のページに戻ってデータを削除してからインストールしてください';
$lang->install->errorCreateTable = '表の作成に失敗しました';
$lang->install->errorImportDemoData = 'demoデータのインポートに失敗しました';

$lang->install->setConfig = '配置ファイル生成';
$lang->install->key = '配置項目';
$lang->install->value = '値';
$lang->install->saveConfig = '配置ファイルを保存します';
$lang->install->save2File = '<div class="alert alert-warning">上のテキストボックスの内容をコピーして "<strong> %s </strong>"で保存してください。今後もこの配置ファイルを更新することができます。</div>';
$lang->install->saved2File = '配置情報は既に" <strong>%s</strong> "で保存されています。今後もこのファイルを更新することができます。';
$lang->install->errorNotSaveConfig = '配置ファイルはまだ保存されていません';

$lang->install->getPriv = 'アカウント設定';
$lang->install->company = '会社名称';
$lang->install->account = '管理者アカウント';
$lang->install->password = '管理者パスワード';
$lang->install->errorEmptyPassword = 'パスワードを入力してください';

$lang->install->groupList['ADMIN']['name'] = '管理者';
$lang->install->groupList['ADMIN']['desc'] = 'システム管理者';
$lang->install->groupList['DEV']['name'] = '研究開発';
$lang->install->groupList['DEV']['desc'] = '研究開発メンバー';
$lang->install->groupList['QA']['name'] = 'テスト';
$lang->install->groupList['QA']['desc'] = 'テストメンバー';
$lang->install->groupList['PM']['name'] = 'プロジェクトマネージャ';
$lang->install->groupList['PM']['desc'] = 'プロジェクトマネージャ';
$lang->install->groupList['PO']['name'] = 'プロダクトマネージャ';
$lang->install->groupList['PO']['desc'] = 'プロダクトマネージャ';
$lang->install->groupList['TD']['name'] = '研究開発主管';
$lang->install->groupList['TD']['desc'] = '研究開発主管';
$lang->install->groupList['PD']['name'] = 'プロダクト主管';
$lang->install->groupList['PD']['desc'] = 'プロダクト主管';
$lang->install->groupList['QD']['name'] = 'テスト主管';
$lang->install->groupList['QD']['desc'] = 'テスト主管';
$lang->install->groupList['TOP']['name'] = '上級管理';
$lang->install->groupList['TOP']['desc'] = '上級管理';
$lang->install->groupList['OTHERS']['name'] = 'その他';
$lang->install->groupList['OTHERS']['desc'] = 'その他';
$lang->install->groupList['LIMITED']['name'] = '制限付きユーザ';
$lang->install->groupList['LIMITED']['desc'] = '制限付きユーザグループ（自分の関連内容のみ編集可能）';

$lang->install->cronList[''] = '定時タスクコントロール';
$lang->install->cronList['moduleName=project&methodName=computeburn'] = 'バーンダウンチャート更新';
$lang->install->cronList['moduleName=report&methodName=remind'] = '毎日タスクリマインド';
$lang->install->cronList['moduleName=svn&methodName=run'] = 'SVNシンクロ';
$lang->install->cronList['moduleName=git&methodName=run'] = 'GITシンクロ';
$lang->install->cronList['moduleName=backup&methodName=backup'] = 'データと添付ファイルをバックアップ';
$lang->install->cronList['moduleName=mail&methodName=asyncSend'] = '非同期発信';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend'] = '非同期でWebhookを送信';
$lang->install->cronList['moduleName=admin&methodName=deleteLog'] = '期限切れログ削除';
$lang->install->cronList['moduleName=todo&methodName=createCycle'] = 'サイクルTodo生成';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = '创建周期性任务';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = '同步Jenkins任务状态';
$lang->install->cronList['moduleName=ci&methodName=exec']               = '执行Jenkins任务';

$lang->install->success = 'インストール成功';
$lang->install->login = '禅道管理システムログイン';
$lang->install->register = '禅道コミュニティサインアップ';

$lang->install->joinZentao = <<<EOT
<p>您已经成功安装禅道管理系统%s，<strong class='text-danger'>请及时删除install.php</strong>。</p><p>友情提示：为了您及时获得禅道的最新动态，请在禅道社区(<a href='https://www.zentao.net' class='alert-link' target='_blank'>www.zentao.net</a>)进行登记。</p>
EOT;

$lang->install->product = array('chanzhi', 'ranzhi', 'xuanxuan');

$lang->install->promotion = '易軟天創の他の製品をお勧めいたします：';
$lang->install->chanzhi = new stdclass();
$lang->install->chanzhi->name = '禅知企業ポータルシステム';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url = 'http://www.chanzhi.org';
$lang->install->chanzhi->desc  = <<<EOD
<ul>
  <li>专业的企业营销门户系统</li>
  <li>功能丰富，操作简洁方便</li>
  <li>大量细节针对SEO优化</li>
  <li>开源免费，不限商用！</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = '然之協同管理システム';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url = 'http://www.zdoo.com';
$lang->install->zdoo->desc  = <<<EOD
<ul>
  <li>客户管理，订单跟踪</li>
  <li>项目任务，公告文档</li>
  <li>收入支出，出帐入账</li>
  <li>论坛博客，动态消息</li>
</ul>
EOD;

$lang->install->xuanxuan = new stdclass();
$lang->install->xuanxuan->name = '喧喧即時チャットソフト';
$lang->install->xuanxuan->logo = 'images/main/xuanxuan.ico';
$lang->install->xuanxuan->url = 'http://www.xuan.im';
$lang->install->xuanxuan->desc  = <<<EOD
<ul>
  <li>轻：轻量级架构，容易部署</li>
  <li>跨：真正完整跨平台解决方案</li>
  <li>美：基于Html5开发，界面美观</li>
  <li>开：开放架构，方便二开集成</li>
</ul>
EOD;

$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name  = '悦库网盘';
$lang->install->ydisk->logo  = 'images/main/ydisk.ico';
$lang->install->ydisk->url   = 'http://www.ydisk.cn';
$lang->install->ydisk->desc  = <<<EOD
<ul>
  <li>绝对私有：只部署在自己的机器上</li>
  <li>海量存储：只取决于您的硬盘大小</li>
  <li>极限传输：只取决于您的网络带宽</li>
  <li>极度安全：十二种权限组合</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name  = '易天物联';
$lang->install->meshiot->logo  = 'images/main/meshiot.ico';
$lang->install->meshiot->url   = 'https://www.meshiot.com';
$lang->install->meshiot->desc  = <<<EOD
<ul>
  <li>超性能网关，一个可管6万个设备</li>
  <li>自研通讯协议，2.5公里穿墙无障碍</li>
  <li>上百款传感器控制器，独创调光系统</li>
  <li>可配电池，对既有场地无任何要求</li>
</ul>
EOD;
