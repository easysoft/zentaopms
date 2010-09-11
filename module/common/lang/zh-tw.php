<?php
/**
 * The common simplified chinese file of ZenTaoMS.
 *
 * This file should be UTF-8 encoded.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009-2010 青島易軟天創網絡科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id: zh-tw.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->arrow        = ' » ';
$lang->colon        = '::';
$lang->comma        = '，';
$lang->dot          = '。';
$lang->at           = ' 于 ';
$lang->downArrow    = '↓';

$lang->zentaoMS     = '禪道管理';
$lang->welcome      = "歡迎使用『%s』{$lang->colon} {$lang->zentaoMS}";
$lang->myControl    = "我的地盤";
$lang->currentPos   = '當前位置：';
$lang->logout       = '退出系統';
$lang->login        = '登錄';
$lang->aboutZenTao  = '關於禪道';
$lang->todayIs      = '今天是%s，';

$lang->reset        = '重填';
$lang->edit         = '編輯';
$lang->copy         = '複製';
$lang->delete       = '刪除';
$lang->close        = '關閉';
$lang->link         = '關聯';
$lang->unlink       = '移除';
$lang->import       = '導入';
$lang->exportCSV    = '導出csv';
$lang->setFileName  = '請輸入檔案名：';
$lang->activate     = '激活';
$lang->save         = '保存';
$lang->confirm      = '確認';
$lang->preview      = '預覽';
$lang->goback       = '返回';
$lang->showHelp     = '顯示幫助';
$lang->closeHelp    = '關閉幫助';
$lang->go           = 'GO!';
$lang->more         = '更多!';

$lang->actions      = '操作';
$lang->comment      = '備註';
$lang->history      = '歷史記錄';
$lang->attatch      = '附件';
$lang->reverse      = '（切換順序）';
$lang->addFiles     = '上傳了附件 ';
$lang->files        = '附件 ';

$lang->selectAll    = '全選';
$lang->notFound     = '抱歉，您訪問的對象並不存在！';
$lang->showAll      = '++ 全部顯示 ++';
$lang->hideClosed   = '-- 隱藏已結束 --';

$lang->feature      = '未來';
$lang->year         = '年';
$lang->workingHour  = '工時';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '狀態';
$lang->openedByAB   = '創建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '類型';

/* 主導航菜單。*/
$lang->menu->index   = '首頁|index|index';
$lang->menu->my      = '我的地盤|my|index';
$lang->menu->product = '產品視圖|product|index';
$lang->menu->project = '項目視圖|project|index';
$lang->menu->qa      = 'QA視圖|qa|index';
$lang->menu->doc     = '文檔視圖|doc|index';
//$lang->menu->forum   = '討論視圖|doc|index';
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
$lang->searchObjects['build']       = 'B:Build';
$lang->searchObjects['release']     = 'R:發佈';
$lang->searchObjects['productplan'] = 'P:產品計劃';
$lang->searchObjects['testtask']    = 'T:測試任務';
$lang->searchTips                   = '輸入編號';

/* 首頁菜單設置。*/
$lang->index->menu->product = '瀏覽產品|product|browse';
$lang->index->menu->project = '瀏覽項目|project|browse';

/* 我的地盤菜單設置。*/
$lang->my->menu->account  = '%s' . $lang->arrow;
$lang->my->menu->todo     = array('link' => '我的TODO|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task     = '我的任務|my|task|';
$lang->my->menu->bug      = '我的Bug|my|bug|';
$lang->my->menu->story    = '我的需求|my|story|';
$lang->my->menu->project  = '我的項目|my|project|';
$lang->my->menu->profile  = array('link' => '我的檔案|my|profile|', 'alias' => 'editprofile');
$lang->todo->menu         = $lang->my->menu;

/* 產品視圖設置。*/
$lang->product->menu->list   = '%s';
$lang->product->menu->story  = array('link' => '需求列表|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->plan   = array('link' => '計劃列表|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release= array('link' => '發佈列表|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap= '路線圖|product|roadmap|productID=%s';
$lang->product->menu->doc    = array('link' => '文檔列表|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view   = '基本信息|product|view|productID=%s';
$lang->product->menu->edit   = '編輯產品|product|edit|productID=%s';
$lang->product->menu->module = '維護模組|tree|browse|productID=%s&view=story';
$lang->product->menu->delete = array('link' => '刪除產品|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->create = array('link' => '新增產品|product|create', 'float' => 'right');
$lang->story->menu           = $lang->product->menu;
$lang->productplan->menu     = $lang->product->menu;
$lang->release->menu         = $lang->product->menu;

/* 項目視圖菜單設置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '任務列表|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => '需求列表|project|story|projectID=%s');
$lang->project->menu->bug       = 'Bug列表|project|bug|projectID=%s';
$lang->project->menu->build     = array('link' => 'Build列表|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->burn      = '燃盡圖|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => '團隊成員|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文檔列表|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '關聯產品|project|manageproducts|projectID=%s';
$lang->project->menu->linkstory = array('link' => '關聯需求|project|linkstory|projectID=%s');
$lang->project->menu->view      = '基本信息|project|view|projectID=%s';
$lang->project->menu->edit      = '編輯項目|project|edit|projectID=%s';
$lang->project->menu->delete    = array('link' => '刪除項目|project|delete|projectID=%s', 'target' => 'hiddenwin');

$lang->project->menu->create = array('link' => '新增項目|project|create', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;
$lang->build->menu           = $lang->project->menu;

/* QA視圖菜單設置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s');

$lang->testtask->menu->product  = '%s';
$lang->testtask->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testtask->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s');
$lang->testtask->menu->testtask = array('link' => '測試任務|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');

/* 文檔視圖菜單設置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => '文檔列表|doc|browse|libID=%s');
$lang->doc->menu->edit    = '編輯文檔庫|doc|editLib|libID=%s';
$lang->doc->menu->module  = '維護模組|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '刪除文檔庫|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '新增文檔庫|doc|createLib', 'float' => 'right');

/* 組織結構視圖菜單設置。*/
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => '用戶列表|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部門維護|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '權限分組|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => '公司管理|company|edit');
$lang->company->menu->addGroup    = array('link' => '添加分組|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '添加用戶|user|create|dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;

/* 用戶信息菜單設置。*/
$lang->user->menu->account  = '%s' . $lang->arrow;
$lang->user->menu->todo     = array('link' => 'TODO列表|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task     = '任務列表|user|task|account=%s';
$lang->user->menu->bug      = 'Bug列表|user|bug|account=%s';
$lang->user->menu->project  = '項目列表|user|project|account=%s';
$lang->user->menu->profile  = array('link' => '用戶信息|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse   = array('link' => '用戶管理|company|browse|', 'float' => 'right');

/* 後台管理菜單設置。*/
$lang->admin->menu->trashes = array('link' => '資源回收筒|action|trash', 'subModule' => 'action');
$lang->admin->menu->convert = array('link' => '從其他系統導入|convert|index', 'subModule' => 'convert');
$lang->convert->menu        = $lang->admin->menu;
$lang->upgrade->menu        = $lang->admin->menu;
$lang->action->menu         = $lang->admin->menu;

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

/* 錯誤提示信息。*/
$lang->error->companyNotFound = "您訪問的域名 %s 沒有對應的公司。";
$lang->error->length          = array("『%s』長度錯誤，應當為『%s』", "『%s』長度應當不超過『%s』，且不小於『%s』。");
$lang->error->reg             = "『%s』不符合格式，應當為:『%s』。";
$lang->error->unique          = "『%s』已經有『%s』這條記錄了。";
$lang->error->notempty        = "『%s』不能為空。";
$lang->error->equal           = "『%s』必須為『%s』。";
$lang->error->int             = array("『%s』應當是數字。", "『%s』應當介於『%s-%s』之間。");
$lang->error->float           = "『%s』應當是數字，可以是小數。";
$lang->error->email           = "『%s』應當為合法的EMAIL。";
$lang->error->date            = "『%s』應當為合法的日期。";
$lang->error->account         = "『%s』應當為合法的用戶名。";
$lang->error->passwordsame    = "兩次密碼應當相等。";
$lang->error->passwordrule    = "密碼應該符合規則，長度至少為六位。";

/* 分頁信息。*/
$lang->pager->noRecord  = "暫時沒有記錄";
$lang->pager->digest    = "共<strong>%s</strong>條記錄,每頁 <strong>%s</strong>條，頁面：<strong>%s/%s</strong> ";
$lang->pager->first     = "首頁";
$lang->pager->pre       = "上頁";
$lang->pager->next      = "下頁";
$lang->pager->last      = "末頁";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "官方網站";
$lang->sponser        = "<a href='http://www.pujia.com' target='_blank'>普加贊助</a>";
$lang->zentaoKeywords = "開源項目管理軟件,項目管理,項目管理軟件,pmp,pms,php框架，國產php框架，scrum工具,scrum管理工具,scrum管理軟件,敏捷項目管理,禪道";
$lang->zentaoDESC     = "禪道項目管理軟件(ZenTaoPMS)是一款國產的，基于LGPL協議，開源免費的項目管理軟件(工具、系統)，同時也是一款scrum管理工具。
    它集產品管理、項目管理、測試管理於一體，同時還包含了事務管理、組織管理等諸多功能，是中小型企業項目管理的首選。禪道項目管理軟件使用PHP + MySQL開發，
基于自主的PHP開發框架──ZenTaoPHP而成。第三方開發者或者企業可以非常方便的開發插件或者進行定製。禪道在手，項目無憂！"; 

/* 時間格式設置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'n月d日 H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'Y年m月d日');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

/* 表情。*/
$lang->smilies->smile       = '微笑';
$lang->smilies->sad         = '悲傷';
$lang->smilies->wink        = '眨眼';
$lang->smilies->tongue      = '吐舌頭';
$lang->smilies->shocked     = '驚訝';
$lang->smilies->eyesdown    = '失望';
$lang->smilies->angry       = '憤怒';
$lang->smilies->cool        = '耍酷';
$lang->smilies->indifferent = '冷漠';
$lang->smilies->sick        = '病中';
$lang->smilies->blush       = '臉紅';
$lang->smilies->angel       = '天使';
$lang->smilies->confused    = '困惑';
$lang->smilies->cry         = '大哭';
$lang->smilies->footinmouth = '保密';
$lang->smilies->biggrin     = '大笑';
$lang->smilies->nerd        = '書獃子';
$lang->smilies->tired       = '好累';
$lang->smilies->rose        = '玫瑰';
$lang->smilies->kiss        = '吻';
$lang->smilies->heart       = '心';
$lang->smilies->hug         = '擁抱';
$lang->smilies->dog         = '狗狗';
$lang->smilies->deadrose    = '殘花';
$lang->smilies->clock       = '時鐘';
$lang->smilies->brokenheart = '傷心';
$lang->smilies->coffee      = '咖啡';
$lang->smilies->computer    = '計算機';
$lang->smilies->devil       = '魔鬼';
$lang->smilies->thumbsup    = '贊同';
$lang->smilies->thumbsdown  = '反對';
$lang->smilies->mail        = '郵件';
