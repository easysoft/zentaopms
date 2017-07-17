<?php
/**
 * The misc module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: zh-tw.php 5128 2013-07-13 08:59:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common = '雜項';
$lang->misc->ping   = '防超時';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = '版本%s';
$lang->misc->zentao->labels['about']   = '關於禪道';
$lang->misc->zentao->labels['support'] = '技術支持';
$lang->misc->zentao->labels['cowin']   = '幫助我們';
$lang->misc->zentao->labels['service'] = '服務列表';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = '升級專業版本';
$lang->misc->zentao->about['official']     = "官方網站";
$lang->misc->zentao->about['changelog']    = "版本歷史";
$lang->misc->zentao->about['license']      = "授權協議";
$lang->misc->zentao->about['extension']    = "插件平台";

$lang->misc->zentao->support['vip']        = "商業技術支持";
$lang->misc->zentao->support['manual']     = "用戶手冊";
$lang->misc->zentao->support['faq']        = "常見問題";
$lang->misc->zentao->support['ask']        = "官方問答";
$lang->misc->zentao->support['qqgroup']    = "官方QQ群";

$lang->misc->zentao->cowin['donate']       = "捐助我們";
$lang->misc->zentao->cowin['reportbug']    = "彙報Bug";
$lang->misc->zentao->cowin['feedback']     = "反饋需求";
$lang->misc->zentao->cowin['recommend']    = "推薦給朋友";
$lang->misc->zentao->cowin['cowinmore']    = "更多方式...";

$lang->misc->zentao->service['zentaotrain']= '禪道使用培訓';
$lang->misc->zentao->service['scrumtrain'] = '敏捷開發培訓';
$lang->misc->zentao->service['idc']        = '禪道在綫託管';
$lang->misc->zentao->service['custom']     = '禪道定製開發';
$lang->misc->zentao->service['install']    = '禪道安裝服務';
$lang->misc->zentao->service['fixissue']   = '禪道問題解決';
$lang->misc->zentao->service['servicemore']= '更多服務...';

$lang->misc->mobile      = "手機訪問";
$lang->misc->noGDLib     = "請用手機瀏覽器訪問：<strong>%s</strong>";
$lang->misc->copyright   = "&copy; 2009 - 2016 <a href='http://www.cnezsoft.com' target='_blank'>青島易軟天創網絡科技有限公司</a> 電話：4006-8899-23 Email：<a href='mailto:co@zentao.net'>co@zentao.net</a>  QQ：1492153927";
$lang->misc->checkTable  = "檢查修復數據表";
$lang->misc->needRepair  = "修復表";
$lang->misc->repairTable = "資料庫表可能因為斷電原因損壞，需要檢查修復！！";
$lang->misc->repairFail  = "修復失敗，請到該資料庫的數據目錄下，嘗試執行<code>myisamchk -r -f %s.MYI</code>進行修復。";
$lang->misc->tableName   = "表名";
$lang->misc->tableStatus = "狀態";
$lang->misc->novice      = "您可能初次使用禪道，是否進入新手模式？";

$lang->user->noticeResetFile = "<h5>普通用戶請聯繫管理員進行修復</h5>
    <h5>管理員請登錄禪道所在的伺服器，創建<span>%s</span>檔案。</h5>
    <p>注意：</p>
    <ol>
    <li>檔案內容為空。</li>
    <li>如果之前檔案存在，刪除之後重新創建。</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = '最新版本';
$lang->misc->feature->detailed = '詳情';

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'增加禪道雲發信功能', 'desc' => '<p>禪道雲發信是禪道聯合SendCloud推出的一項免費發信服務，只有用戶綁定禪道，並通過驗證即可使用。</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'優化富文本編輯器和markdown編輯器', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'調整文檔功能', 'desc' => '<p>增加文檔模組首頁，重新組織文檔庫結構，增加權限</p><p>多種檔案瀏覽方式，文檔支持Markdown，增加文檔權限管理，增加檔案版本管理。</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'首頁自定義', 'desc' => '<p>我的地盤由我做主。現在開始，你可以向首頁添加多種多樣的內容區塊，而且還可以決定如何排列和顯示他們。</p><p>我的地盤、產品、項目、測試模組下均支持首頁自定義功能。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'導航定製', 'desc' => '<p>導航上顯示的項目現在完全由你來決定，不僅僅可以決定在導航上展示哪些內容，還可以決定展示的順序。</p><p>將滑鼠懸浮在導航上稍後會在右側顯示定製按鈕，點擊打開定製對話框，通過點擊切換是否顯示，拖放操作來更改顯示順序。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'批量添加、編輯自定義', 'desc' => '<p>可以在批量添加和批量編輯頁面自定義操作的欄位。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'添加需求、任務、Bug、用例自定義', 'desc' => '<p>可以在添加需求、任務、Bug、用例頁面，自定義部分欄位是否顯示。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'導出自定義', 'desc' => '<p>在導出需求、任務、Bug、用例的時候，用戶可以自定義導出的欄位，也可以保存模板方便每次導出。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'需求、任務、Bug、用例組合檢索功能', 'desc' => '<p>在需求、任務、Bug、用例列表頁面，可以實現模組和標籤的組合檢索。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用戶瞭解禪道使用。</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'產品實現分支功能', 'desc' => '<p>產品增加分支/平台類型，相應的需求、計劃、Bug、用例、模組等都增加分支。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'調整發佈模組', 'desc' => '<p>發佈增加停止維護操作，當發佈停止維護時，創建Bug將不顯示這個發佈。</p><p>發佈中遺留的bug改為手工關聯。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'調整需求和Bug的創建頁面', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'增強安全', 'desc' => '<p>加強對管理員弱口令的檢查。</p><p>寫插件，上傳插件的時候需要創建ok檔案。</p><p>敏感操作增加管理員口令的檢查</p><p>對輸入內容做striptags, specialchars處理。</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'完善細節', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供計劃任務框架', 'desc' => '增加計劃任務框架，加入每日提醒、更新燃盡圖、備份、發信等重要任務。');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供rpm和deb包', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'增加數據表格功能', 'desc' => '<p>可配置數據表格中可顯示的欄位，按照配置欄位顯示想看的數據</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'繼續完善細節', 'desc' => '');
