<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 3481 2012-09-02 05:53:18Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->arrow        = '<span class="icon-arrow">&nbsp; </span>';
$lang->colon        = '::';
$lang->comma        = '，';
$lang->dot          = '。';
$lang->at           = ' 于 ';
$lang->downArrow    = '↓';

$lang->ZenTaoPMS    = '禪道管理';
$lang->welcome      = "歡迎使用『%s』{$lang->colon} {$lang->ZenTaoPMS}";
$lang->myControl    = "我的地盤";
$lang->currentPos   = '當前位置：';
$lang->logout       = '退出';
$lang->login        = '登錄';
$lang->aboutZenTao  = '關於';
$lang->todayIs      = '今天是%s，';
$lang->runInfo      = "<div class='row'><div class='u-1 a-center' id='debugbar'>時間: %s 毫秒, 內存: %s KB, 查詢: %s.  </div></div>";

$lang->reset        = '重填';
$lang->edit         = '編輯';
$lang->copy         = '複製';
$lang->delete       = '刪除';
$lang->close        = '關閉';
$lang->link         = '關聯';
$lang->unlink       = '移除';
$lang->import       = '導入';
$lang->export       = '導出';
$lang->setFileName  = '檔案名：';
$lang->activate     = '激活';
$lang->submitting   = '稍候...';
$lang->save         = '保存';
$lang->confirm      = '確認';
$lang->preview      = '查看';
$lang->goback       = '返回';
$lang->go           = 'GO';
$lang->more         = '更多';

$lang->actions      = '操作';
$lang->comment      = '備註';
$lang->history      = '歷史記錄';
$lang->attatch      = '附件';
$lang->reverse      = '[切換順序]';
$lang->switchDisplay= '[切換顯示]';
$lang->switchHelp   = '切換幫助';
$lang->addFiles     = '上傳了附件 ';
$lang->files        = '附件 ';
$lang->unfold       = '+';
$lang->fold         = '-';

$lang->selectAll     = '全選';
$lang->selectReverse = '反選';
$lang->notFound      = '抱歉，您訪問的對象並不存在！';
$lang->showAll       = '++ 全部顯示 ++';
$lang->hideClosed    = '-- 隱藏已結束 --';

$lang->future       = '未來';
$lang->year         = '年';
$lang->workingHour  = '工時';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '狀態';
$lang->openedByAB   = '創建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '類型';

$lang->common->common = '公有模組';

/* 主導航菜單。*/
$lang->menu->my      = '<span id="mainbg">&nbsp;</span>我的地盤|my|index';
$lang->menu->product = '產品視圖|product|index';
$lang->menu->project = '項目視圖|project|index';
$lang->menu->qa      = '測試視圖|qa|index';
$lang->menu->doc     = '文檔視圖|doc|index';
$lang->menu->report  = '統計視圖|report|index';
$lang->menu->company = '組織視圖|company|index';
$lang->menu->admin   = '後台管理|admin|index';

/* 查詢條中可以選擇的對象列表。*/
$lang->searchObjects['bug']         = 'B:Bug';
$lang->searchObjects['story']       = 'S:需求';
$lang->searchObjects['task']        = 'T:任務';
$lang->searchObjects['testcase']    = 'C:用例';
$lang->searchObjects['project']     = 'P:項目';
$lang->searchObjects['product']     = 'P:產品';
$lang->searchObjects['user']        = 'U:用戶';
$lang->searchObjects['build']       = 'B:版本';
$lang->searchObjects['release']     = 'R:發佈';
$lang->searchObjects['productplan'] = 'P:產品計劃';
$lang->searchObjects['testtask']    = 'T:測試任務';
$lang->searchObjects['doc']         = 'D:文檔';
$lang->searchTips                   = '編號(ctrl+g)';

/* 導入支持的編碼格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 導出檔案的類型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

/* 支持的編碼格式。*/
$lang->exportEncodeList['gbk']   = 'GBK';
$lang->exportEncodeList['big5']  = 'BIG5';
$lang->exportEncodeList['utf-8'] = 'UTF-8';

/* 風格列表。*/
$lang->themes['default']   = '預設';
$lang->themes['green']     = '綠色';
$lang->themes['red']       = '紅色';
$lang->themes['classblue'] = '經典藍';

/* 首頁菜單設置。*/
$lang->index->menu->product = '瀏覽產品|product|browse';
$lang->index->menu->project = '瀏覽項目|project|browse';

/* 我的地盤菜單設置。*/
$lang->my->menu->account        = '<span id="mybg">&nbsp;</span>%s' . $lang->arrow;
$lang->my->menu->index          = '首頁|my|index';
$lang->my->menu->todo           = array('link' => '我的TODO|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = '我的任務|my|task|';
$lang->my->menu->bug            = '我的Bug|my|bug|';
$lang->my->menu->testtask       = '我的測試|my|testtask|';
$lang->my->menu->story          = '我的需求|my|story|';
$lang->my->menu->myProject      = '我的項目|my|project|';
$lang->my->menu->dynamic        = '我的動態|my|dynamic|';
$lang->my->menu->profile        = array('link' => '我的檔案|my|profile|', 'alias' => 'editprofile');
$lang->my->menu->changePassword = '修改密碼|my|changepassword|';
$lang->todo->menu               = $lang->my->menu;

/* 產品視圖設置。*/
$lang->product->menu->list    = '%s';
$lang->product->menu->story   = array('link' => '需求|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->dynamic = '動態|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => '計劃|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => '發佈|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = '路線圖|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => '文檔|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view    = array('link' => '概況|product|view|productID=%s', 'alias' => 'edit');
$lang->product->menu->module  = '模組|tree|browse|productID=%s&view=story';
$lang->product->menu->project = '項目列表|product|project|status=all&productID=%s';
$lang->product->menu->order   = '排序|product|order|productID=%s';
$lang->product->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增產品|product|create', 'float' => 'right');
$lang->product->menu->all     = array('link' => '<span class="icon-all">&nbsp;</span>所有產品|product|index|locate=false', 'float' => 'right');
$lang->story->menu            = $lang->product->menu;
$lang->productplan->menu      = $lang->product->menu;
$lang->release->menu          = $lang->product->menu;

/* 項目視圖菜單設置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '任務|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask,burn');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = '動態|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = '測試申請|project|testtask|projectID=%s';
$lang->project->menu->team      = array('link' => '團隊|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文檔|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '產品|project|manageproducts|projectID=%s';
$lang->project->menu->view      = array('link' => '概況|project|view|projectID=%s', 'alias' => 'edit');
$lang->project->menu->order     = '排序|project|order|projectID=%s';
$lang->project->menu->create    = array('link' => '<span class="icon-add1">&nbsp;</span>新增項目|project|create', 'float' => 'right');
$lang->project->menu->all       = array('link' => '<span class="icon-all">&nbsp;</span>所有項目|project|index|locate=false', 'float' => 'right');
$lang->task->menu               = $lang->project->menu;
$lang->build->menu              = $lang->project->menu;

/* QA視圖菜單設置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');
$lang->testtask->menu           = $lang->testcase->menu;

/* 文檔視圖菜單設置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => '文檔列表|doc|browse|libID=%s');
$lang->doc->menu->edit    = '編輯文檔庫|doc|editLib|libID=%s';
$lang->doc->menu->module  = '維護模組|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '刪除文檔庫|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增文檔庫|doc|createLib', 'float' => 'right');

/* 統計視圖菜單設置。*/
$lang->report->menu->product = array('link' => '產品|report|productinfo');
$lang->report->menu->prj     = array('link' => '項目|report|projectdeviation');
$lang->report->menu->test    = array('link' => '測試|report|bugsummary', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => '組織|report|workload');

/* 組織結構視圖菜單設置。*/
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => '用戶列表|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部門維護|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '權限分組|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => '公司管理|company|edit');
$lang->company->menu->dynamic     = '組織動態|company|dynamic|';
$lang->company->menu->addGroup    = array('link' => '<span class="icon-add1">&nbsp;</span>添加分組|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '<span class="icon-add1">&nbsp;</span>添加用戶|user|create|dept=%s', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;
$lang->user->menu            = $lang->company->menu;

/* 後台管理菜單設置。*/
$lang->admin->menu->index     = array('link' => '首頁|admin|index');
$lang->admin->menu->extension = array('link' => '插件管理|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->editor    = array('link' => '擴展編輯器|editor|index', 'subModule' => 'editor');
$lang->admin->menu->mail      = array('link' => '發信配置|mail|index', 'subModule' => 'mail');
$lang->admin->menu->clearData = array('link' => '清除數據|admin|cleardata');
$lang->admin->menu->convert   = array('link' => '從其他系統導入|convert|index', 'subModule' => 'convert');
$lang->admin->menu->trashes   = array('link' => '資源回收筒|action|trash', 'subModule' => 'action');
$lang->convert->menu          = $lang->admin->menu;
$lang->upgrade->menu          = $lang->admin->menu;
$lang->action->menu           = $lang->admin->menu;
$lang->extension->menu        = $lang->admin->menu;
$lang->editor->menu           = $lang->admin->menu;
$lang->mail->menu             = $lang->admin->menu;

/* 菜單分組。*/
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->action      = 'admin';
$lang->menugroup->extension   = 'admin';
$lang->menugroup->editor      = 'admin';
$lang->menugroup->mail        = 'admin';

/* 錯誤提示信息。*/
$lang->error->companyNotFound = "您訪問的域名 %s 沒有對應的公司。";
$lang->error->length          = array("『%s』長度錯誤，應當為『%s』", "『%s』長度應當不超過『%s』，且不小於『%s』。");
$lang->error->reg             = "『%s』不符合格式，應當為:『%s』。";
$lang->error->unique          = "『%s』已經有『%s』這條記錄了。";
$lang->error->gt              = "『%s』應當大於『%s』。";
$lang->error->ge              = "『%s』應當不小於『%s』。";
$lang->error->notempty        = "『%s』不能為空。";
$lang->error->empty           = "『%s』必須為空。";
$lang->error->equal           = "『%s』必須為『%s』。";
$lang->error->int             = array("『%s』應當是數字。", "『%s』應當介於『%s-%s』之間。");
$lang->error->float           = "『%s』應當是數字，可以是小數。";
$lang->error->email           = "『%s』應當為合法的EMAIL。";
$lang->error->date            = "『%s』應當為合法的日期。";
$lang->error->account         = "『%s』應當為合法的用戶名。";
$lang->error->passwordsame    = "兩次密碼應當相等。";
$lang->error->passwordrule    = "密碼應該符合規則，長度至少為六位。";
$lang->error->accessDenied    = '您沒有訪問權限';

/* 分頁信息。*/
$lang->pager->noRecord  = "暫時沒有記錄";
$lang->pager->digest    = "共<strong>%s</strong>條記錄，每頁 <strong>%s</strong>條，<strong>%s/%s</strong> ";
$lang->pager->first     = "首頁";
$lang->pager->pre       = "上頁";
$lang->pager->next      = "下頁";
$lang->pager->last      = "末頁";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "官方網站";
$lang->chinaScrum     = "<a href='http://www.zentao.net/goto.php?item=chinascrum' target='_blank'>Scrum社區</a> ";
$lang->agileTraining  = "<a href='http://www.zentao.net/goto.php?item=agiletrain' target='_blank'>培訓</a> ";
$lang->donate         = "<a href='http://www.zentao.net/goto.php?item=donate' target='_blank'>捐贈</a> ";
$lang->proVersion     = "<a href='http://www.zentao.net/goto.php?item=proversion&from=footer' target='_blank' class='red'>購買專業版(特惠)！</a> ";

$lang->suhosinInfo = "警告：數據太多，請在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（設置更大的數）。 保存並重新啟動apache，否則會造成部分數據無法保存。";

$lang->noResultsMatch = "沒有匹配結果";

/* 時間格式設置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'n月d日 H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'Y年m月d日');
define('DT_DATE4',     'n月j日');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

include (dirname(__FILE__) . '/menuOrder.php');
