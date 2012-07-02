<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-tw.php 3218 2012-07-01 12:50:38Z zhujinyonging@gmail.com $
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

/* 主菜單順序。*/
$lang->menuOrder[5]  = 'my';
$lang->menuOrder[10] = 'product';
$lang->menuOrder[15] = 'project';
$lang->menuOrder[20] = 'qa';
$lang->menuOrder[25] = 'doc';
$lang->menuOrder[30] = 'report';
$lang->menuOrder[35] = 'company';
$lang->menuOrder[40] = 'admin';

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

$lang->index->menuOrder[5]  = 'product';
$lang->index->menuOrder[10] = 'project';

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
$lang->my->menu->changePassword = '修改密碼|my|changePassword|';
$lang->todo->menu               = $lang->my->menu;

$lang->my->menuOrder[5]  = 'account';
$lang->my->menuOrder[10] = 'index';
$lang->my->menuOrder[15] = 'todo';
$lang->my->menuOrder[20] = 'task';
$lang->my->menuOrder[25] = 'bug';
$lang->my->menuOrder[30] = 'testtask';
$lang->my->menuOrder[35] = 'story';
$lang->my->menuOrder[40] = 'myProject';
$lang->my->menuOrder[45] = 'dynamic';
$lang->my->menuOrder[50] = 'profile';
$lang->my->menuOrder[55] = 'changePassword';
$lang->todo->menuOrder   = $lang->my->menuOrder;

/* 產品視圖設置。*/
$lang->product->menu->list    = '%s';
$lang->product->menu->story   = array('link' => '需求|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->dynamic = '動態|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => '計劃|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => '發佈|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = '路線圖|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => '文檔|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view    = '概況|product|view|productID=%s';
$lang->product->menu->module  = '模組|tree|browse|productID=%s&view=story';
$lang->product->menu->project = '項目列表|product|project|status=all&productID=%s';
$lang->product->menu->order   = '排序|product|order|productID=%s';
$lang->product->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增產品|product|create', 'float' => 'right');
$lang->product->menu->all     = array('link' => '<span class="icon-all">&nbsp;</span>所有產品|product|index|locate=false', 'float' => 'right');
$lang->story->menu            = $lang->product->menu;
$lang->productplan->menu      = $lang->product->menu;
$lang->release->menu          = $lang->product->menu;

$lang->product->menuOrder[5]  = 'story';
$lang->product->menuOrder[10] = 'dynamic';
$lang->product->menuOrder[15] = 'plan';
$lang->product->menuOrder[20] = 'release';
$lang->product->menuOrder[25] = 'roadmap';
$lang->product->menuOrder[30] = 'doc';
$lang->product->menuOrder[35] = 'project';
$lang->product->menuOrder[40] = 'view';
$lang->product->menuOrder[45] = 'module';
$lang->product->menuOrder[50] = 'order';
$lang->product->menuOrder[55] = 'create';
$lang->product->menuOrder[60] = 'all';

$lang->story->menuOrder       = $lang->product->menuOrder;
$lang->productplan->menuOrder = $lang->product->menuOrder;
$lang->release->menuOrder     = $lang->product->menuOrder;

/* 項目視圖菜單設置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '任務|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s', 'alias' => 'linkstory', 'subModule' => 'story');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = '動態|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = '測試申請|project|testtask|projectID=%s';
$lang->project->menu->burn      = '燃盡圖|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => '團隊|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文檔|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '產品|project|manageproducts|projectID=%s';
$lang->project->menu->view      = '概況|project|view|projectID=%s';
$lang->project->menu->order     = '排序|project|order|projectID=%s';
$lang->project->menu->create    = array('link' => '<span class="icon-add1">&nbsp;</span>新增項目|project|create', 'float' => 'right');
$lang->project->menu->copy      = array('link' => '<span class="icon-copy">&nbsp;</span>複製項目|project|create|projectID=&copyProjectID=%s', 'float' => 'right');
$lang->project->menu->all       = array('link' => '<span class="icon-all">&nbsp;</span>所有項目|project|index|locate=false', 'float' => 'right');
$lang->task->menu               = $lang->project->menu;
$lang->build->menu              = $lang->project->menu;

$lang->project->menuOrder[5]  = 'task';
$lang->project->menuOrder[10] = 'story';
$lang->project->menuOrder[15] = 'bug';
$lang->project->menuOrder[20] = 'build';
$lang->project->menuOrder[25] = 'testtask';
$lang->project->menuOrder[30] = 'burn';
$lang->project->menuOrder[35] = 'team';
$lang->project->menuOrder[40] = 'dynamic';
$lang->project->menuOrder[45] = 'doc';
$lang->project->menuOrder[50] = 'product';
$lang->project->menuOrder[55] = 'linkstory';
$lang->project->menuOrder[60] = 'view';
$lang->project->menuOrder[65] = 'order';
$lang->project->menuOrder[70] = 'create';
$lang->project->menuOrder[75] = 'copy';
$lang->project->menuOrder[80] = 'all';
$lang->task->menuOrder        = $lang->project->menuOrder;
$lang->build->menuOrder       = $lang->project->menuOrder;

/* QA視圖菜單設置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s');

$lang->bug->menuOrder[0]  = 'product';
$lang->bug->menuOrder[5]  = 'bug';
$lang->bug->menuOrder[10] = 'testcase';
$lang->bug->menuOrder[15] = 'testtask';

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');
$lang->testtask->menu           = $lang->testcase->menu;

$lang->testcase->menuOrder[0]  = 'product';
$lang->testcase->menuOrder[5]  = 'bug';
$lang->testcase->menuOrder[10] = 'testcase';
$lang->testcase->menuOrder[15] = 'testtask';
$lang->testtask->menuOrder     = $lang->testcase->menuOrder;

/* 文檔視圖菜單設置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => '文檔列表|doc|browse|libID=%s');
$lang->doc->menu->edit    = '編輯文檔庫|doc|editLib|libID=%s';
$lang->doc->menu->module  = '維護模組|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '刪除文檔庫|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增文檔庫|doc|createLib', 'float' => 'right');

$lang->doc->menuOrder[5]  = 'browse';
$lang->doc->menuOrder[10] = 'edit';
$lang->doc->menuOrder[15] = 'module';
$lang->doc->menuOrder[20] = 'delete';
$lang->doc->menuOrder[25] = 'create';

/* 統計視圖菜單設置。*/
$lang->report->menu->prj     = array('link' => '項目|report|projectdeviation');
$lang->report->menu->product = array('link' => '產品|report|productinfo');
$lang->report->menu->test    = array('link' => '測試|report|bugsummary');
$lang->report->menu->staff   = array('link' => '員工|report|workload');

$lang->report->menuOrder[5]  = 'prj';
$lang->report->menuOrder[10] = 'product';
$lang->report->menuOrder[15] = 'test';
$lang->report->menuOrder[20] = 'staff';

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

$lang->company->menuOrder[0]  = 'name';
$lang->company->menuOrder[5]  = 'browseUser';
$lang->company->menuOrder[10] = 'dept';
$lang->company->menuOrder[15] = 'browseGroup';
$lang->company->menuOrder[20] = 'edit';
$lang->company->menuOrder[25] = 'dynamic';
$lang->company->menuOrder[30] = 'addGroup';
$lang->company->menuOrder[35] = 'addUser';
$lang->dept->menuOrder        = $lang->company->menuOrder;
$lang->group->menuOrder       = $lang->company->menuOrder;

/* 用戶信息菜單設置。*/
$lang->user->menu->account     = '%s' . $lang->arrow;
$lang->user->menu->todo        = array('link' => 'TODO列表|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task        = '任務列表|user|task|account=%s';
$lang->user->menu->bug         = 'Bug列表|user|bug|account=%s';
$lang->user->menu->dynamic     = '用戶動態|user|dynamic|type=today&account=%s';
$lang->user->menu->projectList = '項目列表|user|project|account=%s';
$lang->user->menu->profile     = array('link' => '用戶信息|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse      = array('link' => '<span class="icon-title">&nbsp;</span>用戶管理|company|browse|', 'float' => 'right');

$lang->user->menuOrder[0]  = 'account';
$lang->user->menuOrder[5]  = 'todo';
$lang->user->menuOrder[10] = 'task';
$lang->user->menuOrder[15] = 'bug';
$lang->user->menuOrder[20] = 'dynamic';
$lang->user->menuOrder[25] = 'projectList';
$lang->user->menuOrder[30] = 'profile';
$lang->user->menuOrder[35] = 'browse';

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

$lang->admin->menuOrder[5]  = 'index';
$lang->admin->menuOrder[10] = 'extension';
$lang->admin->menuOrder[15] = 'editor';
$lang->admin->menuOrder[20] = 'mail';
$lang->admin->menuOrder[25] = 'clearData';
$lang->admin->menuOrder[30] = 'convert';
$lang->admin->menuOrder[35] = 'trashes';
$lang->convert->menuOrder   = $lang->admin->menuOrder;
$lang->upgrade->menuOrder   = $lang->admin->menuOrder;
$lang->action->menuOrder    = $lang->admin->menuOrder;
$lang->extension->menuOrder = $lang->admin->menuOrder;
$lang->editor->menuOrder    = $lang->admin->menuOrder;
$lang->mail->menuOrder      = $lang->admin->menuOrder;

/*菜單設置：分組設置。*/
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

/* 模組順序。*/
$lang->moduleOrder[0]   = 'index';
$lang->moduleOrder[5]   = 'my';
$lang->moduleOrder[10]  = 'todo';
$lang->moduleOrder[15]  = 'product';
$lang->moduleOrder[20]  = 'story';
$lang->moduleOrder[25]  = 'productplan';
$lang->moduleOrder[30]  = 'release';
$lang->moduleOrder[35]  = 'project';
$lang->moduleOrder[40]  = 'task';
$lang->moduleOrder[45]  = 'build';
$lang->moduleOrder[50]  = 'qa';
$lang->moduleOrder[55]  = 'bug';
$lang->moduleOrder[60]  = 'testcase';
$lang->moduleOrder[65]  = 'testtask';
$lang->moduleOrder[70]  = 'doc';
$lang->moduleOrder[75]  = 'svn';
$lang->moduleOrder[80]  = 'company';
$lang->moduleOrder[85]  = 'dept';
$lang->moduleOrder[90]  = 'group';
$lang->moduleOrder[95]  = 'user';
$lang->moduleOrder[100] = 'tree';
$lang->moduleOrder[105] = 'search';
$lang->moduleOrder[110] = 'admin';
$lang->moduleOrder[115] = 'api';
$lang->moduleOrder[120] = 'file';
$lang->moduleOrder[125] = 'misc';
$lang->moduleOrder[130] = 'action';

/* 方法順序。*/
$lang->index->methodOrder[0] = 'index';

$lang->my->methodOrder[0]  = 'index';
$lang->my->methodOrder[5]  = 'todo';
$lang->my->methodOrder[10] = 'task';
$lang->my->methodOrder[15] = 'bug';
$lang->my->methodOrder[20] = 'testTask';
$lang->my->methodOrder[25] = 'testCase';
$lang->my->methodOrder[30] = 'story';
$lang->my->methodOrder[35] = 'project';
$lang->my->methodOrder[40] = 'profile';
$lang->my->methodOrder[45] = 'dynamic';
$lang->my->methodOrder[50] = 'editProfile';
$lang->my->methodOrder[55] = 'changePassword';

$lang->todo->methodOrder[5]  = 'create';
$lang->todo->methodOrder[10] = 'batchCreate';
$lang->todo->methodOrder[15] = 'edit';
$lang->todo->methodOrder[20] = 'view';
$lang->todo->methodOrder[25] = 'delete';
$lang->todo->methodOrder[30] = 'export';
$lang->todo->methodOrder[35] = 'mark';
$lang->todo->methodOrder[40] = 'import2Today';

$lang->product->methodOrder[0]  = 'index';
$lang->product->methodOrder[5]  = 'browse';
$lang->product->methodOrder[10] = 'create';
$lang->product->methodOrder[15] = 'view';
$lang->product->methodOrder[20] = 'edit';
$lang->product->methodOrder[25] = 'order';
$lang->product->methodOrder[30] = 'delete';
$lang->product->methodOrder[35] = 'roadmap';
$lang->product->methodOrder[40] = 'doc';
$lang->product->methodOrder[45] = 'dynamic';
$lang->product->methodOrder[50] = 'project';
$lang->product->methodOrder[55] = 'ajaxGetProjects';
$lang->product->methodOrder[60] = 'ajaxGetPlans';

$lang->story->methodOrder[] = 'create';
$lang->story->methodOrder[] = 'batchCreate';
$lang->story->methodOrder[] = 'edit';
$lang->story->methodOrder[] = 'export';
$lang->story->methodOrder[] = 'delete';
$lang->story->methodOrder[] = 'view';
$lang->story->methodOrder[] = 'change';
$lang->story->methodOrder[] = 'review';
$lang->story->methodOrder[] = 'close';
$lang->story->methodOrder[] = 'batchClose';
$lang->story->methodOrder[] = 'activate';
$lang->story->methodOrder[] = 'tasks';
$lang->story->methodOrder[] = 'report';
$lang->story->methodOrder[] = 'ajaxGetProjectStories';
$lang->story->methodOrder[] = 'ajaxGetProductStories';

$lang->productplan->methodOrder[] = 'browse';
$lang->productplan->methodOrder[] = 'create';
$lang->productplan->methodOrder[] = 'edit';
$lang->productplan->methodOrder[] = 'delete';
$lang->productplan->methodOrder[] = 'view';
$lang->productplan->methodOrder[] = 'linkStory';
$lang->productplan->methodOrder[] = 'unlinkStory';

$lang->release->methodOrder[] = 'browse';
$lang->release->methodOrder[] = 'create';
$lang->release->methodOrder[] = 'edit';
$lang->release->methodOrder[] = 'delete';
$lang->release->methodOrder[] = 'view';
$lang->release->methodOrder[] = 'ajaxGetStoriesAndBugs';
$lang->release->methodOrder[] = 'exportStoriesAndBugs';

$lang->project->methodOrder[] = 'index';
$lang->project->methodOrder[] = 'view';
$lang->project->methodOrder[] = 'browse';
$lang->project->methodOrder[] = 'create';
$lang->project->methodOrder[] = 'edit';
$lang->project->methodOrder[] = 'order';
$lang->project->methodOrder[] = 'delete';
$lang->project->methodOrder[] = 'task';
$lang->project->methodOrder[] = 'grouptask';
$lang->project->methodOrder[] = 'importtask';
$lang->project->methodOrder[] = 'importBug';
$lang->project->methodOrder[] = 'story';
$lang->project->methodOrder[] = 'build';
$lang->project->methodOrder[] = 'testtask';
$lang->project->methodOrder[] = 'bug';
$lang->project->methodOrder[] = 'burn';
$lang->project->methodOrder[] = 'computeBurn';
$lang->project->methodOrder[] = 'burnData';
$lang->project->methodOrder[] = 'team';
$lang->project->methodOrder[] = 'doc';
$lang->project->methodOrder[] = 'dynamic';
$lang->project->methodOrder[] = 'manageProducts';
$lang->project->methodOrder[] = 'manageMembers';
$lang->project->methodOrder[] = 'unlinkMember';
$lang->project->methodOrder[] = 'linkStory';
$lang->project->methodOrder[] = 'unlinkStory';
$lang->project->methodOrder[] = 'ajaxGetProducts';

$lang->task->methodOrder[] = 'create';
$lang->task->methodOrder[] = 'batchCreate';
$lang->task->methodOrder[] = 'batchEdit';
$lang->task->methodOrder[] = 'edit';
$lang->task->methodOrder[] = 'assignTo';
$lang->task->methodOrder[] = 'start';
$lang->task->methodOrder[] = 'finish';
$lang->task->methodOrder[] = 'cancel';
$lang->task->methodOrder[] = 'close';
$lang->task->methodOrder[] = 'batchClose';
$lang->task->methodOrder[] = 'activate';
$lang->task->methodOrder[] = 'delete';
$lang->task->methodOrder[] = 'view';
$lang->task->methodOrder[] = 'export';
$lang->task->methodOrder[] = 'confirmStoryChange';
$lang->task->methodOrder[] = 'ajaxGetUserTasks';
$lang->task->methodOrder[] = 'ajaxGetProjectTasks';
$lang->task->methodOrder[] = 'report';

$lang->build->methodOrder[] = 'create';
$lang->build->methodOrder[] = 'edit';
$lang->build->methodOrder[] = 'delete';
$lang->build->methodOrder[] = 'view';
$lang->build->methodOrder[] = 'ajaxGetProductBuilds';
$lang->build->methodOrder[] = 'ajaxGetProjectBuilds';

$lang->qa->methodOrder[] = 'index';

$lang->bug->methodOrder[] = 'index';
$lang->bug->methodOrder[] = 'browse';
$lang->bug->methodOrder[] = 'create';
$lang->bug->methodOrder[] = 'confirmBug';
$lang->bug->methodOrder[] = 'view';
$lang->bug->methodOrder[] = 'edit';
$lang->bug->methodOrder[] = 'assignTo';
$lang->bug->methodOrder[] = 'resolve';
$lang->bug->methodOrder[] = 'activate';
$lang->bug->methodOrder[] = 'close';
$lang->bug->methodOrder[] = 'report';
$lang->bug->methodOrder[] = 'export';
$lang->bug->methodOrder[] = 'confirmStoryChange';
$lang->bug->methodOrder[] = 'delete';
$lang->bug->methodOrder[] = 'saveTemplate';
$lang->bug->methodOrder[] = 'deleteTemplate';
$lang->bug->methodOrder[] = 'customFields';
$lang->bug->methodOrder[] = 'ajaxGetUserBugs';
$lang->bug->methodOrder[] = 'ajaxGetModuleOwner';

$lang->testcase->methodOrder[] = 'index';
$lang->testcase->methodOrder[] = 'browse';
$lang->testcase->methodOrder[] = 'create';
$lang->testcase->methodOrder[] = 'batchCreate';
$lang->testcase->methodOrder[] = 'view';
$lang->testcase->methodOrder[] = 'edit';
$lang->testcase->methodOrder[] = 'delete';
$lang->testcase->methodOrder[] = 'export';
$lang->testcase->methodOrder[] = 'confirmStoryChange';

$lang->testtask->methodOrder[] = 'index';
$lang->testtask->methodOrder[] = 'create';
$lang->testtask->methodOrder[] = 'browse';
$lang->testtask->methodOrder[] = 'view';
$lang->testtask->methodOrder[] = 'cases';
$lang->testtask->methodOrder[] = 'edit';
$lang->testtask->methodOrder[] = 'delete';
$lang->testtask->methodOrder[] = 'batchAssign';
$lang->testtask->methodOrder[] = 'linkcase';
$lang->testtask->methodOrder[] = 'unlinkcase';
$lang->testtask->methodOrder[] = 'runcase';
$lang->testtask->methodOrder[] = 'results';

$lang->doc->methodOrder[] = 'index';
$lang->doc->methodOrder[] = 'browse';
$lang->doc->methodOrder[] = 'createLib';
$lang->doc->methodOrder[] = 'editLib';
$lang->doc->methodOrder[] = 'deleteLib';
$lang->doc->methodOrder[] = 'create';
$lang->doc->methodOrder[] = 'view';
$lang->doc->methodOrder[] = 'edit';
$lang->doc->methodOrder[] = 'delete';

$lang->svn->methodOrder[] = 'diff';
$lang->svn->methodOrder[] = 'cat';
$lang->svn->methodOrder[] = 'apiSync';

$lang->moduleOrder[80]  = 'company';
$lang->moduleOrder[85]  = 'dept';
$lang->moduleOrder[90]  = 'group';
$lang->moduleOrder[95]  = 'user';
$lang->moduleOrder[100] = 'tree';
$lang->moduleOrder[105] = 'search';
$lang->moduleOrder[110] = 'extension';
$lang->moduleOrder[115] = 'api';
$lang->moduleOrder[120] = 'file';
$lang->moduleOrder[125] = 'misc';
$lang->moduleOrder[130] = 'action';

$lang->company->methodOrder[] = 'index';
$lang->company->methodOrder[] = 'browse';
$lang->company->methodOrder[] = 'edit';
$lang->company->methodOrder[] = 'dynamic';
$lang->company->methodOrder[] = 'dffort';

$lang->dept->methodOrder[] = 'browse';
$lang->dept->methodOrder[] = 'updateOrder';
$lang->dept->methodOrder[] = 'manageChild';
$lang->dept->methodOrder[] = 'delete';

$lang->group->methodOrder[] = 'browse';
$lang->group->methodOrder[] = 'create';
$lang->group->methodOrder[] = 'edit';
$lang->group->methodOrder[] = 'copy';
$lang->group->methodOrder[] = 'delete';
$lang->group->methodOrder[] = 'managePriv';
$lang->group->methodOrder[] = 'manageMember';

$lang->user->methodOrder[] = 'create';
$lang->user->methodOrder[] = 'view';
$lang->user->methodOrder[] = 'edit';
$lang->user->methodOrder[] = 'delete';
$lang->user->methodOrder[] = 'todo';
$lang->user->methodOrder[] = 'task';
$lang->user->methodOrder[] = 'bug';
$lang->user->methodOrder[] = 'project';
$lang->user->methodOrder[] = 'dynamic';
$lang->user->methodOrder[] = 'profile';
$lang->user->methodOrder[] = 'ajaxGetUser';

$lang->tree->methodOrder[] = 'browse';
$lang->tree->methodOrder[] = 'updateOrder';
$lang->tree->methodOrder[] = 'manageChild';
$lang->tree->methodOrder[] = 'edit';
$lang->tree->methodOrder[] = 'delete';
$lang->tree->methodOrder[] = 'ajaxGetOptionMenu';
$lang->tree->methodOrder[] = 'ajaxGetSonModules';

$lang->search->methodOrder[] = 'buildForm';
$lang->search->methodOrder[] = 'buildQuery';
$lang->search->methodOrder[] = 'saveQuery';
$lang->search->methodOrder[] = 'deleteQuery';
$lang->search->methodOrder[] = 'select';

$lang->admin->methodOrder[] = 'index';

$lang->api->methodOrder[] = 'getModel';

$lang->file->methodOrder[] = 'download';
$lang->file->methodOrder[] = 'edit';
$lang->file->methodOrder[] = 'delete';
$lang->file->methodOrder[] = 'ajaxUpload';

$lang->misc->methodOrder[] = 'ping';

$lang->action->methodOrder[] = 'trash';
$lang->action->methodOrder[] = 'undelete';

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
