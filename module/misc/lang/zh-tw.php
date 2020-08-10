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
$lang->misc->api    = 'https://api.zentao.net';
$lang->misc->enApi  = 'http://api.zentao.pm';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = '版本%s';
$lang->misc->zentao->labels['about']   = '關於禪道';
$lang->misc->zentao->labels['support'] = '技術支持';
$lang->misc->zentao->labels['cowin']   = '幫助我們';
$lang->misc->zentao->labels['service'] = '服務列表';
$lang->misc->zentao->labels['others']  = '其他產品';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['proversion']   = '升級專業版本';
$lang->misc->zentao->about['official']     = "官方網站";
$lang->misc->zentao->about['changelog']    = "版本歷史";
$lang->misc->zentao->about['license']      = "授權協議";
$lang->misc->zentao->about['extension']    = "插件平台";
$lang->misc->zentao->about['follow']       = "關注我們";

$lang->misc->zentao->support['vip']        = "商業技術支持";
$lang->misc->zentao->support['manual']     = "用戶手冊";
$lang->misc->zentao->support['faq']        = "常見問題";
$lang->misc->zentao->support['ask']        = "官方問答";
$lang->misc->zentao->support['video']      = "使用視頻";
$lang->misc->zentao->support['qqgroup']    = "官方QQ群";

$lang->misc->zentao->cowin['reportbug']    = "彙報Bug";
$lang->misc->zentao->cowin['feedback']     = "反饋需求";
$lang->misc->zentao->cowin['translate']    = "參與翻譯";
$lang->misc->zentao->cowin['recommend']    = "推薦給朋友";

$lang->misc->zentao->service['zentaotrain']= '禪道使用培訓';
$lang->misc->zentao->service['idc']        = '禪道在綫託管';
$lang->misc->zentao->service['custom']     = '禪道定製開發';
$lang->misc->zentao->service['servicemore']= '更多服務...';

global $config;
$lang->misc->zentao->others['chanzhi']  = "<img src='{$config->webRoot}theme/default/images/main/chanzhi.ico' /> 蟬知門戶";
$lang->misc->zentao->others['zdoo']     = "<img src='{$config->webRoot}theme/default/images/main/zdoo.ico' /> ZDOO";
$lang->misc->zentao->others['xuanxuan'] = "<img src='{$config->webRoot}theme/default/images/main/xuanxuan.ico' /> 喧喧聊天";
$lang->misc->zentao->others['ydisk']    = "<img src='{$config->webRoot}theme/default/images/main/ydisk.ico' /> 悅庫網盤";
$lang->misc->zentao->others['meshiot' ] = "<img src='{$config->webRoot}theme/default/images/main/meshiot.ico' /> 易天物聯";

$lang->misc->mobile      = "手機訪問";
$lang->misc->noGDLib     = "請用手機瀏覽器訪問：<strong>%s</strong>";
$lang->misc->copyright   = "&copy; 2009 - 2018 <a href='http://www.cnezsoft.com' target='_blank'>青島易軟天創網絡科技有限公司</a> 電話：4006-8899-23 Email：<a href='mailto:co@zentao.net'>co@zentao.net</a>  QQ：1492153927";
$lang->misc->checkTable  = "檢查修復數據表";
$lang->misc->needRepair  = "修復表";
$lang->misc->repairTable = "資料庫表可能因為斷電原因損壞，需要檢查修復！！";
$lang->misc->repairFail  = "修復失敗，請到該資料庫的數據目錄下，嘗試執行<code>myisamchk -r -f %s.MYI</code>進行修復。";
$lang->misc->connectFail = "連接資料庫失敗，錯誤：%s，<br/> 請檢查mysql錯誤日誌，排查錯誤。";
$lang->misc->tableName   = "表名";
$lang->misc->tableStatus = "狀態";
$lang->misc->novice      = "您可能初次使用禪道，是否進入新手模式？";
$lang->misc->showAnnual  = '新增年度總結功能';
$lang->misc->annualDesc  = '12.0版本後，新增年度總結功能，可以到『統計->年度總結』頁面查看。 是否現在<a href="%s" target="_blank" id="showAnnual" class="btn btn-mini btn-primary">查看</a>';
$lang->misc->remind      = '新功能提醒';

$lang->misc->noticeRepair = "<h5>普通用戶請聯繫管理員進行修復</h5>
    <h5>管理員請登錄禪道所在的伺服器，創建<span>%s</span>檔案。</h5>
    <p>注意：</p>
    <ol>
    <li>檔案內容為空。</li>
    <li>如果之前檔案存在，刪除之後重新創建。</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest  = '最新版本';
$lang->misc->feature->detailed = '詳情';

$lang->misc->releaseDate['12.4.1']      = '2020-08-10';
$lang->misc->releaseDate['12.4.stable'] = '2020-07-28';
$lang->misc->releaseDate['12.3.3']      = '2020-07-02';
$lang->misc->releaseDate['12.3.2']      = '2020-06-01';
$lang->misc->releaseDate['12.3.1']      = '2020-05-15';
$lang->misc->releaseDate['12.3']        = '2020-04-08';
$lang->misc->releaseDate['12.2']        = '2020-03-25';
$lang->misc->releaseDate['12.1']        = '2020-03-10';
$lang->misc->releaseDate['12.0.1']      = '2020-02-12';
$lang->misc->releaseDate['12.0']        = '2020-01-03';
$lang->misc->releaseDate['11.7']        = '2019-11-28';
$lang->misc->releaseDate['11.6.5']      = '2019-11-08';
$lang->misc->releaseDate['11.6.4']      = '2019-10-17';
$lang->misc->releaseDate['11.6.3']      = '2019-09-24';
$lang->misc->releaseDate['11.6.2']      = '2019-09-06';
$lang->misc->releaseDate['11.6.1']      = '2019-08-23';
$lang->misc->releaseDate['11.6.stable'] = '2019-07-12';
$lang->misc->releaseDate['11.5.2']      = '2019-06-26';
$lang->misc->releaseDate['11.5.1']      = '2019-06-24';
$lang->misc->releaseDate['11.5.stable'] = '2019-05-08';
$lang->misc->releaseDate['11.4.1']      = '2019-04-08';
$lang->misc->releaseDate['11.4.stable'] = '2019-03-25';
$lang->misc->releaseDate['11.3.stable'] = '2019-02-27';
$lang->misc->releaseDate['11.2.stable'] = '2019-01-30';
$lang->misc->releaseDate['11.1.stable'] = '2019-01-04';
$lang->misc->releaseDate['11.0.stable'] = '2018-12-21';
$lang->misc->releaseDate['10.6.stable'] = '2018-11-20';
$lang->misc->releaseDate['10.5.stable'] = '2018-10-25';
$lang->misc->releaseDate['10.4.stable'] = '2018-09-28';
$lang->misc->releaseDate['10.3.stable'] = '2018-08-10';
$lang->misc->releaseDate['10.2.stable'] = '2018-08-02';
$lang->misc->releaseDate['10.0.stable'] = '2018-06-26';
$lang->misc->releaseDate['9.8.stable']  = '2018-01-17';
$lang->misc->releaseDate['9.7.stable']  = '2017-12-22';
$lang->misc->releaseDate['9.6.stable']  = '2017-11-06';
$lang->misc->releaseDate['9.5.1']       = '2017-09-27';
$lang->misc->releaseDate['9.3.beta']    = '2017-06-21';
$lang->misc->releaseDate['9.1.stable']  = '2017-03-23';
$lang->misc->releaseDate['9.0.beta']    = '2017-01-03';
$lang->misc->releaseDate['8.3.stable']  = '2016-11-09';
$lang->misc->releaseDate['8.2.stable']  = '2016-05-17';
$lang->misc->releaseDate['7.4.beta']    = '2015-11-13';
$lang->misc->releaseDate['7.2.stable']  = '2015-05-22';
$lang->misc->releaseDate['7.1.stable']  = '2015-03-07';
$lang->misc->releaseDate['6.3.stable']  = '2014-11-07';

$lang->misc->feature->all['12.4.1'][] = array('title'=>'修復Bug', 'desc' => '');

$lang->misc->feature->all['12.4.stable'][] = array('title'=>'修復Bug', 'desc' => '');

$lang->misc->feature->all['12.3.3'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['12.3.2'][] = array('title'=>'修復工作流。', 'desc' => '');
$lang->misc->feature->all['12.3.1'][] = array('title'=>'修復重要程度高的Bug。', 'desc' => '');
$lang->misc->feature->all['12.3'][]   = array('title'=>'整合單元測試，打通持續整合閉環。', 'desc' => '');
$lang->misc->feature->all['12.2'][]   = array('title'=>'增加父子需求，兼容最新喧喧。', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title'=>'增加構建功能', 'desc' => '<p>增加構建功能，整合Jenkins進行構建</p>');
$lang->misc->feature->all['12.0.1'][] = array('title'=>'修復Bug', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'將代碼功能版本瀏覽功能轉移到開源版', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'增加年度總結', 'desc' => '根據角色顯示年度總結。');
$lang->misc->feature->all['12.0'][]   = array('title'=>'完善細節，修復Bug', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title'=>'完善細節，修復Bug', 'desc' => '<p>增加用戶是否使用敏捷概念的選擇</p><p>webhook類型中增加企業微信</p><p>實現到釘釘個人消息的通知</p>');
$lang->misc->feature->all['11.6.5'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'改善國際版界面', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title'=>'添加翻譯功能', 'desc' => '');

$lang->misc->feature->all['11.5.2'][] = array('title'=>'增加禪道安全性，增加登錄禪道弱口令檢查', 'desc' => '');
$lang->misc->feature->all['11.5.1'][] = array('title'=>'新增第三方應用免密登錄禪道，修復Bug', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'新增動態過濾機制', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'整合新版本客戶端', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title'=>'完善細節，修復Bug', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array("title"=>"完善細節，修復Bug", "desc" => "<p>增強測試任務管理</p><p>優化計劃、發佈、版本關聯{$lang->storyCommon}和bug的交互</p><p>文檔庫可以自定義是否顯示子分類裡的文檔</p><p>修復bug，完善細節</p>");

$lang->misc->feature->all['11.3.stable'][] = array('title'=>'完善細節，修復Bug', 'desc' => '<p>計劃添加子計劃功能</p><p>優化chosen交互</p><p>添加時區設置</p><p>優化文檔庫和文檔</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title'=>'完善細節，修復Bug', 'desc' => '<p>增加升級日誌和升級後資料庫檢查的功能</p><p>修復禪道整合客戶端和其他若干bug，完善細節</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title'=>'主要修復Bug。', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title'=>'禪道整合喧喧', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'調整備份機制', 'desc' => '<p>增加備份設置，備份更加靈活</p><p>顯示備份進度</p><p>可以更改備份目錄</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title'=>'優化和調整菜單', 'desc' => '<p>調整後台菜單</p><p>調整我的地盤和項目的二級菜單</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'調整文檔顯示', 'desc' => '<p>調整文檔庫左側的佈局方式</p><p>文檔庫左側導航底部增加篩選條件</p>');
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'調整子任務邏輯，優化父子任務顯示。', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'優化調整新界面', 'desc' => '<p>詳情頁面還原我們之前的排版佈局</p><p>重構添加用戶頁面的表單</p><p>用例執行時，如果用戶手工選擇了通過，寫結果的時候不要更新用例狀態</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'用戶機器休眠登錄失效後，重新刷新session', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'提升現有的介面機制', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'整合喧喧IM', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'全新的界面和交互體驗', 'desc' => '<ol><li>全新的我的地盤</li><li>全新的動態頁面</li><li>全新的產品主頁</li><li>全新的產品概況</li><li>全新的路線圖</li><li>全新的項目主頁</li><li>全新的項目概況</li><li>全新的測試主頁</li><li>全新的文檔主頁</li><li>我的地盤新增工作統計區塊</li><li>我的地盤待辦區塊可以直接添加、編輯、完成待辦</li><li>產品主頁新增產品統計區塊</li><li>產品主頁新增產品總覽區塊</li><li>項目主頁新增項目統計區塊</li><li>項目主頁新增項目總覽區塊</li><li>測試主頁新增測試統計區塊</li><li>所有產品、產品主頁、所有項目、項目主頁、測試主頁等按鈕從二級導航右側移動到了左側</li><li>項目任務列表看板、燃盡圖、樹狀圖、分組查看等按鈕從三級導航中移動到二級導航中，樹狀圖、分組查看和任務列表整合到一個下拉列表中</li><li>項目下二級導航中Bug、版本、測試單三個跟測試相關的導航整合到一個下拉列表中</li><li>版本、測試單列表按照產品分組展示，佈局更加合理</li><li>文檔左側增加樹狀圖顯示</li><li>文檔增加快速訪問功能，包括最近更新、我的文檔、我的收藏三個入口</li><li>文檔增加收藏功能</li><ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'實現集中的消息處理機制', 'desc' => '<p>郵件，短信，webhook都放統一的消息發送</p><p>移植ZDOO裡面的消息通知功能</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'實現周期性待辦功能', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'增加指派給我的區塊', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'項目可以選擇多個測試單生成報告', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'調整國際版，增加英文Demo數據。', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'新增了webhook功能', 'desc' => '實現與倍冾、釘釘的消息通知介面');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'新增禪道操作獲取積分的功能', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'項目任務新增了多人任務和子任務功能', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'產品視圖新增了產品綫功能', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'新增受限操作', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'升級框架，增強程序安全', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'完善測試視圖', 'desc' => '<p>增加測試套件、公共測試庫和測試總結功能</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'支持測試步驟分組', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'增加禪道雲發信功能', 'desc' => '<p>禪道雲發信是禪道聯合SendCloud推出的一項免費發信服務，只有用戶綁定禪道，並通過驗證即可使用。</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'優化富文本編輯器和markdown編輯器', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'調整文檔功能', 'desc' => '<p>增加文檔模組首頁，重新組織文檔庫結構，增加權限</p><p>多種檔案瀏覽方式，文檔支持Markdown，增加文檔權限管理，增加檔案版本管理。</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'首頁自定義', 'desc' => '<p>我的地盤由我做主。現在開始，你可以向首頁添加多種多樣的內容區塊，而且還可以決定如何排列和顯示他們。</p><p>我的地盤、產品、項目、測試模組下均支持首頁自定義功能。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'導航定製', 'desc' => '<p>導航上顯示的項目現在完全由你來決定，不僅僅可以決定在導航上展示哪些內容，還可以決定展示的順序。</p><p>將滑鼠懸浮在導航上稍後會在右側顯示定製按鈕，點擊打開定製對話框，通過點擊切換是否顯示，拖放操作來更改顯示順序。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'批量添加、編輯自定義', 'desc' => '<p>可以在批量添加和批量編輯頁面自定義操作的欄位。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"添加{$lang->storyCommon}、任務、Bug、用例自定義", 'desc' => "<p>可以在添加{$lang->storyCommon}、任務、Bug、用例頁面，自定義部分欄位是否顯示。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'導出自定義', 'desc' => "<p>在導出{$lang->storyCommon}、任務、Bug、用例的時候，用戶可以自定義導出的欄位，也可以保存模板方便每次導出。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"{$lang->storyCommon}、任務、Bug、用例組合檢索功能", 'desc' => "<p>在{$lang->storyCommon}、任務、Bug、用例列表頁面，可以實現模組和標籤的組合檢索。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用戶瞭解禪道使用。</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'產品實現分支功能', 'desc' => "<p>產品增加分支/平台類型，相應的{$lang->storyCommon}、計劃、Bug、用例、模組等都增加分支。</p>");
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'調整發佈模組', 'desc' => '<p>發佈增加停止維護操作，當發佈停止維護時，創建Bug將不顯示這個發佈。</p><p>發佈中遺留的bug改為手工關聯。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>"調整{$lang->storyCommon}和Bug的創建頁面", 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'增強安全', 'desc' => '<p>加強對管理員弱口令的檢查。</p><p>寫插件，上傳插件的時候需要創建ok檔案。</p><p>敏感操作增加管理員口令的檢查</p><p>對輸入內容做striptags, specialchars處理。</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'完善細節', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供計劃任務框架', 'desc' => '增加計劃任務框架，加入每日提醒、更新燃盡圖、備份、發信等重要任務。');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供rpm和deb包', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'增加數據表格功能', 'desc' => '<p>可配置數據表格中可顯示的欄位，按照配置欄位顯示想看的數據</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'繼續完善細節', 'desc' => '');
