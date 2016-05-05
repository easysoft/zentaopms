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
$lang->misc->repairTable = "資料庫表可以因為斷電原因損壞，需要檢查修復！！";
$lang->misc->tableName   = "表名";
$lang->misc->tableStatus = "狀態";
$lang->misc->novice      = "您可能初次使用禪道，是否進入新手模式？";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest         = '最新版本';
$lang->misc->feature->all             = array();
$lang->misc->feature->all['latest']   = array();
$lang->misc->feature->all['latest'][] = array('title'=>'首頁自定義', 'desc' => '<p>我的地盤由我做主。現在開始，你可以向首頁添加多種多樣的內容區塊，而且還可以決定如何排列和顯示他們。</p><p>我的地盤、產品、項目、測試模組下均支持首頁自定義功能。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'導航定製', 'desc' => '<p>導航上顯示的項目現在完全由你來決定，不僅僅可以決定在導航上展示哪些內容，還可以決定展示的順序。</p><p>將滑鼠懸浮在導航上稍後會在右側顯示定製按鈕，點擊打開定製對話框，通過點擊切換是否顯示，拖放操作來更改顯示順序。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'批量添加、編輯自定義', 'desc' => '<p>可以在批量添加和批量編輯頁面自定義操作的欄位。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'添加需求、任務、Bug、用例自定義', 'desc' => '<p>可以在添加需求、任務、Bug、用例頁面，自定義部分欄位是否顯示。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'導出自定義', 'desc' => '<p>在導出需求、任務、Bug、用例的時候，用戶可以自定義導出的欄位，也可以保存模板方便每次導出。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'需求、任務、Bug、用例組合檢索功能', 'desc' => '<p>在需求、任務、Bug、用例列表頁面，可以實現模組和標籤的組合檢索。</p>');
$lang->misc->feature->all['latest'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用戶瞭解禪道使用。</p>');
