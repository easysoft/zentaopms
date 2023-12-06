<?php
/**
 * The upgrade module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: zh-tw.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->upgrade->common          = '升級';
$lang->upgrade->start           = '開始';
$lang->upgrade->result          = '升級結果';
$lang->upgrade->fail            = '升級失敗';
$lang->upgrade->successTip      = '升級成功';
$lang->upgrade->success         = "<p><i class='icon icon-check-circle'></i></p><p>恭喜您！</p><p>您的禪道已經成功升級。</p>";
$lang->upgrade->tohome          = '訪問禪道';
$lang->upgrade->license         = '禪道項目管理軟件已更換授權協議至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning        = '警告';
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
$lang->upgrade->afterDeleted   = '請執行上面命令刪除檔案， 刪除後刷新！';
$lang->upgrade->mergeProgram   = '數據遷移';
$lang->upgrade->mergeTips      = '數據遷移提示';
$lang->upgrade->toPMS15Guide   = '禪道開源版15版本升級';
$lang->upgrade->toPRO10Guide   = '禪道專業版10版本升級';
$lang->upgrade->toBIZ5Guide    = '禪道企業版5版本升級';
$lang->upgrade->toMAXGuide     = '禪道旗艦版版本升級';
$lang->upgrade->to15Desc       = <<<EOD
<p>尊敬的用戶，禪道從15版本開始系統功能做了重大升級，主要改動如下：</p>
<p><strong>一、增加了項目集概念</strong></p>
<p>項目集是一組相互關聯，且被協調管理的項目集合，處于最高層級，屬於戰略層面的概念。它可以進行多層級管理，幫助管理者站在宏觀的視角去制定戰略方向和分配資源。</p>
<p><strong>二、明確了產品和項目概念</strong></p>
<p>產品是定義做什麼，主要管理需求；項目是定義如何做，主要是在規定的時間、預算和質量目標範圍內完成項目的各種工作，可以通過敏捷迭代的方式，也可以通過瀑布階段的方式，屬於戰役層面的管理。</p>
<p><strong>三、增加了項目模型概念</strong></p>
<p>新版本在敏捷管理模型的基礎上增加了瀑布管理模型（旗艦版提供），後續還會支持看板管理模型，幫助項目團隊按需選擇適合的項目管理方式。</p>
<p><strong>四、增加了執行概念</strong></p>
<p>新版本中，根據選擇管理模型的不同，一個項目可以包含多個迭代/衝刺或階段，我們把多個迭代/衝刺或階段統稱為執行，通過執行去完成項目的任務，交付最終的結果。</p>
<p><strong>五、調整了導航結構</strong></p>
<p>將一級導航調整到了界面左側，同時增加了多應用切換的全新交互體驗。</p>
<br/>
<p>您可以在綫體驗最新版本的功能，以決定是否啟用新的模式：<a class='text-info' href='http://zentaomax.demo.zentao.net' target='_blank'>最新版演示demo</a></p>
<p>您還可以下載新版本功能介紹PPT：<a class='text-info' href='https://dl.cnezsoft.com/zentao/zentaoconcept.pdf' target='_blank'>最新版功能介紹PPT</a></p>
<video src="https://dl.cnezsoft.com/vedio/program0716.mp4"  width="100%" controls ="controls"></video>
<p style="text-align:center"><small>禪道15版本介紹</small></p>
<br/>
<p><strong>請問您計劃如何使用禪道的新版本呢？</strong></p>
EOD;

$lang->upgrade->mergeProgramDesc = <<<EOD
<p>接下來我們會把之前歷史{$lang->productCommon}和{$lang->projectCommon}數據遷移到項目集和項目下，遷移的情況如下：</p><br />
<h4>情況一：以{$lang->productCommon}綫組織的{$lang->productCommon}和{$lang->projectCommon} </h4>
<p>可以將整個{$lang->productCommon}綫及其下面的{$lang->productCommon}和{$lang->projectCommon}遷移到一個項目集中，當然您也可以根據需要分開遷移。</p>
<h4>情況二：以{$lang->productCommon}組織的{$lang->projectCommon} </h4>
<p>可以選擇多個{$lang->productCommon}及其下面的{$lang->projectCommon}遷移到一個項目集中，也可以選擇某一個{$lang->productCommon}和{$lang->productCommon}下面的{$lang->projectCommon}遷移到項目集中。</p>
<h4>情況三：獨立的{$lang->projectCommon}</h4>
<p>可以選擇若干個{$lang->projectCommon}遷移到一個項目集中，也可以獨立遷移。</p>
<h4>情況四：關聯多個{$lang->productCommon}的{$lang->projectCommon}</h4>
<p>可以選擇這些{$lang->projectCommon}歸屬於某個新項目下。</p>
EOD;

$lang->upgrade->to15Mode['classic'] = '經典管理模式';
$lang->upgrade->to15Mode['new']     = '全新項目集管理模式';

$lang->upgrade->selectedModeTips['classic'] = '後續您還可以在後台-自定義裡面切換為全新項目集管理的模式。';
$lang->upgrade->selectedModeTips['new']     = '切換為項目集管理模式需要對之前的數據進行歸併處理，系統會引導您完成這個操作。';

$lang->upgrade->line          = '產品綫';
$lang->upgrade->allLines      = "所有{$lang->productCommon}綫";
$lang->upgrade->program       = '目標項目集和項目';
$lang->upgrade->existProgram  = '已有項目集';
$lang->upgrade->existProject  = '已有項目';
$lang->upgrade->existLine     = '已有' . $lang->productCommon . '綫';
$lang->upgrade->product       = $lang->productCommon;
$lang->upgrade->project       = '迭代';
$lang->upgrade->repo          = '版本庫';
$lang->upgrade->mergeRepo     = '歸併版本庫';
$lang->upgrade->setProgram    = '設置項目所屬項目集';
$lang->upgrade->dataMethod    = '數據遷移方式';
$lang->upgrade->begin         = '開始日期';
$lang->upgrade->end           = '結束日期';
$lang->upgrade->selectProject = '目標項目';
$lang->upgrade->programName   = '項目集名稱';
$lang->upgrade->projectName   = '項目名稱';

$lang->upgrade->newProgram         = '新建';
$lang->upgrade->editedName         = '調整後名稱';
$lang->upgrade->projectEmpty       = '所屬項目不能為空！';
$lang->upgrade->mergeSummary       = "尊敬的用戶，您的系統中共有%s個{$lang->productCommon}，%s個{$lang->projectCommon}等待遷移。";
$lang->upgrade->mergeByProductLine = "以{$lang->productCommon}綫組織的{$lang->productCommon}和{$lang->projectCommon}：將整個{$lang->productCommon}綫及其下面的{$lang->productCommon}和{$lang->projectCommon}歸併到一個項目集和項目中，也可以分開歸併。";
$lang->upgrade->mergeByProduct     = "以{$lang->productCommon}組織的{$lang->projectCommon}：可以選擇多個{$lang->productCommon}及其下面的{$lang->projectCommon}歸併到一個項目集和項目中，也可以選擇某一個{$lang->productCommon}將其下面所屬的{$lang->projectCommon}歸併到項目集和項目中。";
$lang->upgrade->mergeByProject     = "獨立的{$lang->projectCommon}：可以選擇若干{$lang->projectCommon}歸併到一個項目中，也可以獨立歸併。";
$lang->upgrade->mergeByMoreLink    = "關聯多個{$lang->productCommon}的{$lang->projectCommon}：選擇一個或多個{$lang->projectCommon}歸併到一個項目集和項目中。";
$lang->upgrade->mergeRepoTips      = "將選中的版本庫歸併到所選產品下。";
$lang->upgrade->needBuild4Add      = '本次升級需要創建索引。請到 [後台->系統->重建索引] 頁面，重新創建索引。';
$lang->upgrade->errorEngineInnodb  = '您當前的資料庫不支持使用InnoDB數據表引擎，請修改為MyISAM後重試。';
$lang->upgrade->duplicateProject   = "同一個項目集內項目名稱不能重複，請調整重名的項目名稱";

$lang->upgrade->projectType['project']   = "把歷史的{$lang->projectCommon}作為項目升級";
$lang->upgrade->projectType['execution'] = "把歷史的{$lang->projectCommon}作為執行升級";

$lang->upgrade->createProjectTip = <<<EOT
<p>升級後歷史的{$lang->projectCommon}一一對應新版本中的項目。</p>
<p>系統會根據歷史{$lang->projectCommon}分別創建一個與該{$lang->projectCommon}同名的執行，並將之前{$lang->projectCommon}的任務、需求、Bug等數據遷移至執行中。</p>
EOT;

$lang->upgrade->createExecutionTip = <<<EOT
<p>系統會把歷史的{$lang->projectCommon}作為執行進行升級。</p>
<p>升級後歷史的{$lang->projectCommon}數據將對應新版本中項目下的執行。</p>
EOT;

include dirname(__FILE__) . '/version.php';
