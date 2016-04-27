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
$lang->misc->feature->lastest       = '最新版本';
$lang->misc->feature->all = array();
$lang->misc->feature->all['latest']   = array();
$lang->misc->feature->all['latest'][] = array('title'=>'可自定義內容的首頁', 'desc' => '<p>你的地盤由你做主。現在開始，你可以向首頁添加多種多樣的內容區塊，而且還可以決定如何排列和顯示他們。</p>', 'img' => '1.gif');
$lang->misc->feature->all['latest'][] = array('title'=>'完全定製所有導航內容', 'desc' => '<p>導航上顯示的項目現在完全由你來決定。將滑鼠懸浮在導航上稍後會在右側顯示定製按鈕，點擊打開定製對話框，通過點擊切換項目是否顯示，拖拽導航項目來更改顯示順序。</p>', 'img' => '2.gif');
