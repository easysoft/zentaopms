<?php
/**
 * The upgrade module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin wuhongjie
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->upgrade->common = 'アップグレード';
$lang->upgrade->result = 'アップグレード結果';
$lang->upgrade->fail = 'アップグレード失敗';
$lang->upgrade->success = 'アップグレード成功';
$lang->upgrade->tohome = '禅道アクセス';
$lang->upgrade->license = '禅道プロジェクト管理ソフトは Z PUBLIC LICENSE(ZPL) 1.2 にライセンス契約を更新しました。';
$lang->upgrade->warnning = '注意';
$lang->upgrade->checkExtension = '検査プラグイン';
$lang->upgrade->consistency = '整合性検査';
$lang->upgrade->warnningContent = <<<EOT
<p>升级有危险，请先备份数据库，以防万一。</p>
<pre>
1. 可以通过phpMyAdmin进行备份。
2. 使用mysql命令行的工具。
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span> 
   要将上面红色的部分分别替换成对应的用户名和禅道系统的数据库名。
   比如： mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD = 'コマンドラインを開き<strong style="color:#ed980f"＞echo＞%s</strong>を実行します。';
$lang->upgrade->createFileLinuxCMD = 'コマンドラインで実行：<stong style="color:#ed980f">touch%s</strong>';
$lang->upgrade->setStatusFile = '<h4>アップグレード前に以下の操作を実行してください：</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>或者删掉"<strong style="color:#ed980f">%s</strong>" 这个文件 ，重新创建一个<strong style="color:#ed980f">ok.txt</strong>文件，不需要内容。</li>
                                      </ul>
                                      <p><strong style="color:red">我已经仔细阅读上面提示且完成上述工作，<a href="#" onclick="location.reload()">继续更新</a></strong></p>';
$lang->upgrade->selectVersion = 'バージョン選択';
$lang->upgrade->continue = 'つづく';
$lang->upgrade->noteVersion = 'データが失われた恐れがありますので、正しいバージョンを選択して下さい。';
$lang->upgrade->fromVersion = '元のバージョン';
$lang->upgrade->toVersion = 'アップグレードへ';
$lang->upgrade->confirm = '実行するSQL文を確認してください';
$lang->upgrade->sureExecute = '実行確認';
$lang->upgrade->forbiddenExt = '次のプラグインは新しいバージョンと互換性がなく、自動的に無効になっています。';
$lang->upgrade->updateFile = '添付ファイル情報を更新する必要があります。';
$lang->upgrade->noticeSQL = 'データベースエラー(標準と異なる為)、修復失敗。以下のSQL文を実行し、画面をリフレッシュして下さい。';
$lang->upgrade->afterDeleted = '以上のファイルは削除できませんでした、 削除してからページを更新してください。';

include dirname(__FILE__) . '/version.php';
