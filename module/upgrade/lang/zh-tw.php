<?php
/**
 * The upgrade module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: zh-tw.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->upgrade->common  = '升級';
$lang->upgrade->result  = '升級結果';
$lang->upgrade->fail    = '升級失敗';
$lang->upgrade->success = "<p><i class='icon icon-check-circle'></i></p><p>恭喜您！</p><p>您的禪道已經成功升級。</p>";
$lang->upgrade->tohome  = '訪問禪道';
$lang->upgrade->license = '禪道項目管理軟件已更換授權協議至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning= '警告';
$lang->upgrade->checkExtension  = '檢查插件';
$lang->upgrade->consistency     = '一致性檢查';
$lang->upgrade->warnningContent = <<<EOT
<p>升級有危險，請先備份資料庫，以防萬一。</p>
<pre>
1. 可以通過phpMyAdmin進行備份。
2. 使用mysql命令行的工具。
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   要將上面紅色的部分分別替換成對應的用戶名和禪道系統的資料庫名。
   比如： mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD   = '打開命令行，執行<strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = '在命令行執行: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>升級之前請先完成下面的操作：</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>或者刪掉"<strong style="color:#ed980f">%s</strong>" 這個檔案 ，重新創建一個<strong style="color:#ed980f">ok.txt</strong>檔案，不需要內容。</li>
                                      </ul>
                                      <p><strong style="color:red">我已經仔細閲讀上面提示且完成上述工作，<a href="#" onclick="location.reload()">繼續更新</a></strong></p>';
$lang->upgrade->selectVersion  = '選擇版本';
$lang->upgrade->continue       = '繼續';
$lang->upgrade->noteVersion    = "務必選擇正確的版本，否則會造成數據丟失。";
$lang->upgrade->fromVersion    = '原來的版本';
$lang->upgrade->toVersion      = '升級到';
$lang->upgrade->confirm        = '確認要執行的SQL語句';
$lang->upgrade->sureExecute    = '確認執行';
$lang->upgrade->forbiddenExt   = '以下插件與新版本不兼容，已經自動禁用：';
$lang->upgrade->updateFile     = '需要更新附件信息。';
$lang->upgrade->noticeSQL      = '檢查到你的資料庫跟標準不一致，嘗試修復失敗。請執行以下SQL語句，再刷新頁面檢查。';
$lang->upgrade->afterDeleted   = '以上檔案未能刪除， 刪除後刷新！';
$lang->upgrade->mergeProgram   = '數據遷移';
$lang->upgrade->mergeTips      = '數據遷移提示';
$lang->upgrade->to20Demo       = '查看20版本Demo';
$lang->upgrade->demoURL        = 'http://zentao20.demo.zentao.net';
$lang->upgrade->videoURL       = 'https://qc.zentao.net/zentao20.mp4';
$lang->upgrade->to20Tips       = '禪道20版本升級提示';
$lang->upgrade->to20Button     = '我已經做好備份，開始升級吧！';
$lang->upgrade->to20TipsHeader = "<p>尊敬的用戶，感謝對禪道的支持。自20版本開始，禪道全面升級成為通用的項目管理平台。相關介紹請看如下視頻(如視頻無法正常播放，請直接訪問 <a href='http://zentao20.demo.zentao.net/zentao20.mp4' target='_blank'><u>禪道20版本介紹</u></a>)：</p><br />";
$lang->upgrade->to20Desc       = <<<EOD
<div class='text-warning' style='width:60%; margin: auto;'>
  <p>友情提示：</p>
  <ol>
    <li>您可以先安裝一個20版本的禪道，體驗一下里邊的概念和流程。</li>
    <li>20版本禪道改動比較大，升級之前請做好備份。</li>
    <li>請放心升級，即使第一次升級不到位，後續還可以再調整，不會影響系統數據。</li>
  </ol>
</div>
EOD;
$lang->upgrade->mergeProgramDesc = <<<EOD
<p>接下來我們會把之前歷史產品和迭代數據遷移到項目集和項目下，遷移的方案如下：</p><br />
<h4>方案一：以產品綫組織的產品和迭代 </h4>
<p>可以將整個產品綫及其下面的產品和迭代遷移到一個項目集和項目中，當然您也可以根據需要分開遷移。</p>
<h4>方案二：以產品組織的迭代 </h4>
<p>可以選擇多個產品及其下面的迭代遷移到一個項目集和項目中，也可以選擇某一個產品和產品下面的迭代遷移到項目集和項目中。</p>
<h4>方案三：獨立的迭代</h4>
<p>可以選擇若干個迭代遷移到一個項目集中，也可以獨立遷移。</p>
<h4>方案四：關聯多個產品的迭代</h4>
<p>可以選擇這些迭代歸屬於某個新項目下。</p>
EOD;

$lang->upgrade->line         = '產品綫';
$lang->upgrade->program      = '目標項目集和項目';
$lang->upgrade->existProgram = '已有項目集';
$lang->upgrade->existProject = '已有項目';
$lang->upgrade->product      = $lang->productCommon;
$lang->upgrade->project      = '迭代';
$lang->upgrade->repo         = '版本庫';
$lang->upgrade->mergeRepo    = '歸併版本庫';

$lang->upgrade->newProgram         = '新建';
$lang->upgrade->projectEmpty       = '項目不能爲空！';
$lang->upgrade->mergeSummary       = "尊敬的用戶，您的系統中共有%s個產品，%s個迭代等待遷移。";
$lang->upgrade->mergeByProductLine = "以產品綫組織的產品和迭代：將整個產品綫及其下面的產品和迭代歸併到一個項目集和項目中，也可以分開歸併。";
$lang->upgrade->mergeByProduct     = "以產品組織的迭代：可以選擇多個產品及其下面的迭代歸併到一個項目集和項目中，也可以選擇某一個產品將其下面所屬的迭代歸併到項目集和項目中。";
$lang->upgrade->mergeByProject     = "獨立的迭代：可以選擇若干迭代歸併到一個項目中，也可以獨立歸併。";
$lang->upgrade->mergeByMoreLink    = "關聯多個產品的迭代：選擇這個迭代歸屬於哪一個產品。";
$lang->upgrade->mergeRepoTips      = "將選中的版本庫歸併到所選產品下。";

include dirname(__FILE__) . '/version.php';
