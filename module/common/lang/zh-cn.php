<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->arrow        = '<span class="icon-arrow">&nbsp; </span>';
$lang->colon        = '::';
$lang->comma        = '，';
$lang->dot          = '。';
$lang->at           = ' 于 ';
$lang->downArrow    = '↓';

$lang->ZenTaoPMS    = '禅道管理';
$lang->welcome      = "欢迎使用『%s』{$lang->colon} {$lang->ZenTaoPMS}";
$lang->myControl    = "我的地盘";
$lang->currentPos   = '当前位置：';
$lang->logout       = '退出';
$lang->login        = '登录';
$lang->aboutZenTao  = '关于';
$lang->todayIs      = '今天是%s，';
$lang->runInfo      = "<div class='row'><div class='u-1 a-center' id='debugbar'>时间: %s 毫秒, 内存: %s KB, 查询: %s.  </div></div>";

$lang->reset        = '重填';
$lang->edit         = '编辑';
$lang->copy         = '复制';
$lang->delete       = '删除';
$lang->close        = '关闭';
$lang->link         = '关联';
$lang->unlink       = '移除';
$lang->import       = '导入';
$lang->export       = '导出';
$lang->setFileName  = '文件名：';
$lang->activate     = '激活';
$lang->submitting   = '稍候...';
$lang->save         = '保存';
$lang->confirm      = '确认';
$lang->preview      = '查看';
$lang->goback       = '返回';
$lang->go           = 'GO';
$lang->more         = '更多';

$lang->actions      = '操作';
$lang->comment      = '备注';
$lang->history      = '历史记录';
$lang->attatch      = '附件';
$lang->reverse      = '[切换顺序]';
$lang->switchDisplay= '[切换显示]';
$lang->switchHelp   = '切换帮助';
$lang->addFiles     = '上传了附件 ';
$lang->files        = '附件 ';
$lang->unfold       = '+';
$lang->fold         = '-';

$lang->selectAll     = '全选';
$lang->selectReverse = '反选';
$lang->notFound      = '抱歉，您访问的对象并不存在！';
$lang->showAll       = '++ 全部显示 ++';
$lang->hideClosed    = '-- 隐藏已结束 --';

$lang->future       = '未来';
$lang->year         = '年';
$lang->workingHour  = '工时';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '状态';
$lang->openedByAB   = '创建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '类型';

$lang->common->common = '公有模块';

/* 主导航菜单。*/
$lang->menu->my      = '<span id="mainbg">&nbsp;</span>我的地盘|my|index';
$lang->menu->product = '产品视图|product|index';
$lang->menu->project = '项目视图|project|index';
$lang->menu->qa      = '测试视图|qa|index';
$lang->menu->doc     = '文档视图|doc|index';
$lang->menu->report  = '统计视图|report|index';
$lang->menu->company = '组织视图|company|index';
$lang->menu->admin   = '后台管理|admin|index';

/* 主菜单顺序。*/
$lang->menuOrder[5]  = 'my';
$lang->menuOrder[10] = 'product';
$lang->menuOrder[15] = 'project';
$lang->menuOrder[20] = 'qa';
$lang->menuOrder[25] = 'doc';
$lang->menuOrder[30] = 'report';
$lang->menuOrder[35] = 'company';
$lang->menuOrder[40] = 'admin';

/* 查询条中可以选择的对象列表。*/
$lang->searchObjects['bug']         = 'B:Bug';
$lang->searchObjects['story']       = 'S:需求';
$lang->searchObjects['task']        = 'T:任务';
$lang->searchObjects['testcase']    = 'C:用例';
$lang->searchObjects['project']     = 'P:项目';
$lang->searchObjects['product']     = 'P:产品';
$lang->searchObjects['user']        = 'U:用户';
$lang->searchObjects['build']       = 'B:版本';
$lang->searchObjects['release']     = 'R:发布';
$lang->searchObjects['productplan'] = 'P:产品计划';
$lang->searchObjects['testtask']    = 'T:测试任务';
$lang->searchObjects['doc']         = 'D:文档';
$lang->searchTips                   = '编号(ctrl+g)';

/* 导入支持的编码格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 导出文件的类型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

/* 支持的编码格式。*/
$lang->exportEncodeList['gbk']   = 'GBK';
$lang->exportEncodeList['big5']  = 'BIG5';
$lang->exportEncodeList['utf-8'] = 'UTF-8';

/* 风格列表。*/
$lang->themes['default']   = '默认';
$lang->themes['green']     = '绿色';
$lang->themes['red']       = '红色';
$lang->themes['classblue'] = '经典蓝';

/* 首页菜单设置。*/
$lang->index->menu->product = '浏览产品|product|browse';
$lang->index->menu->project = '浏览项目|project|browse';

$lang->index->menuOrder[5]  = 'product';
$lang->index->menuOrder[10] = 'project';

/* 我的地盘菜单设置。*/
$lang->my->menu->account        = '<span id="mybg">&nbsp;</span>%s' . $lang->arrow;
$lang->my->menu->index          = '首页|my|index';
$lang->my->menu->todo           = array('link' => '我的TODO|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task           = '我的任务|my|task|';
$lang->my->menu->bug            = '我的Bug|my|bug|';
$lang->my->menu->testtask       = '我的测试|my|testtask|';
$lang->my->menu->story          = '我的需求|my|story|';
$lang->my->menu->myProject      = '我的项目|my|project|';
$lang->my->menu->dynamic        = '我的动态|my|dynamic|';
$lang->my->menu->profile        = array('link' => '我的档案|my|profile|', 'alias' => 'editprofile');
$lang->my->menu->changePassword = '修改密码|my|changePassword|';
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

/* 产品视图设置。*/
$lang->product->menu->list    = '%s';
$lang->product->menu->story   = array('link' => '需求|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->dynamic = '动态|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => '计划|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => '发布|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = '路线图|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => '文档|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view    = '概况|product|view|productID=%s';
$lang->product->menu->module  = '模块|tree|browse|productID=%s&view=story';
$lang->product->menu->project = '项目列表|product|project|status=all&productID=%s';
$lang->product->menu->order   = '排序|product|order|productID=%s';
$lang->product->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增产品|product|create', 'float' => 'right');
$lang->product->menu->all     = array('link' => '<span class="icon-all">&nbsp;</span>所有产品|product|index|locate=false', 'float' => 'right');
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

/* 项目视图菜单设置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '任务|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s', 'alias' => 'linkstory', 'subModule' => 'story');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = '动态|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = '测试申请|project|testtask|projectID=%s';
$lang->project->menu->burn      = '燃尽图|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => '团队|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文档|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '产品|project|manageproducts|projectID=%s';
$lang->project->menu->view      = '概况|project|view|projectID=%s';
$lang->project->menu->order     = '排序|project|order|projectID=%s';
$lang->project->menu->create    = array('link' => '<span class="icon-add1">&nbsp;</span>新增项目|project|create', 'float' => 'right');
$lang->project->menu->copy      = array('link' => '<span class="icon-copy">&nbsp;</span>复制项目|project|create|projectID=&copyProjectID=%s', 'float' => 'right');
$lang->project->menu->all       = array('link' => '<span class="icon-all">&nbsp;</span>所有项目|project|index|locate=false', 'float' => 'right');
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

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s');

$lang->bug->menuOrder[0]  = 'product';
$lang->bug->menuOrder[5]  = 'bug';
$lang->bug->menuOrder[10] = 'testcase';
$lang->bug->menuOrder[15] = 'testtask';

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');
$lang->testtask->menu           = $lang->testcase->menu;

$lang->testcase->menuOrder[0]  = 'product';
$lang->testcase->menuOrder[5]  = 'bug';
$lang->testcase->menuOrder[10] = 'testcase';
$lang->testcase->menuOrder[15] = 'testtask';
$lang->testtask->menuOrder     = $lang->testcase->menuOrder;

/* 文档视图菜单设置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => '文档列表|doc|browse|libID=%s');
$lang->doc->menu->edit    = '编辑文档库|doc|editLib|libID=%s';
$lang->doc->menu->module  = '维护模块|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '删除文档库|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增文档库|doc|createLib', 'float' => 'right');

$lang->doc->menuOrder[5]  = 'browse';
$lang->doc->menuOrder[10] = 'edit';
$lang->doc->menuOrder[15] = 'module';
$lang->doc->menuOrder[20] = 'delete';
$lang->doc->menuOrder[25] = 'create';

/* 统计视图菜单设置。*/
$lang->report->menu->prj     = array('link' => '项目|report|projectdeviation');
$lang->report->menu->product = array('link' => '产品|report|productinfo');
$lang->report->menu->test    = array('link' => '测试|report|bugsummary');
$lang->report->menu->staff   = array('link' => '员工|report|workload');

$lang->report->menuOrder[5]  = 'prj';
$lang->report->menuOrder[10] = 'product';
$lang->report->menuOrder[15] = 'test';
$lang->report->menuOrder[20] = 'staff';

/* 组织结构视图菜单设置。*/
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => '用户列表|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部门维护|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '权限分组|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => '公司管理|company|edit');
$lang->company->menu->dynamic     = '组织动态|company|dynamic|';
$lang->company->menu->addGroup    = array('link' => '<span class="icon-add1">&nbsp;</span>添加分组|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '<span class="icon-add1">&nbsp;</span>添加用户|user|create|dept=%s', 'subModule' => 'user', 'float' => 'right');
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

/* 用户信息菜单设置。*/
$lang->user->menu->account     = '%s' . $lang->arrow;
$lang->user->menu->todo        = array('link' => 'TODO列表|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task        = '任务列表|user|task|account=%s';
$lang->user->menu->bug         = 'Bug列表|user|bug|account=%s';
$lang->user->menu->dynamic     = '用户动态|user|dynamic|type=today&account=%s';
$lang->user->menu->projectList = '项目列表|user|project|account=%s';
$lang->user->menu->profile     = array('link' => '用户信息|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse      = array('link' => '<span class="icon-title">&nbsp;</span>用户管理|company|browse|', 'float' => 'right');

$lang->user->menuOrder[0]  = 'account';
$lang->user->menuOrder[5]  = 'todo';
$lang->user->menuOrder[10] = 'task';
$lang->user->menuOrder[15] = 'bug';
$lang->user->menuOrder[20] = 'dynamic';
$lang->user->menuOrder[25] = 'projectList';
$lang->user->menuOrder[30] = 'profile';
$lang->user->menuOrder[35] = 'browse';

/* 后台管理菜单设置。*/
$lang->admin->menu->index     = array('link' => '首页|admin|index');
$lang->admin->menu->extension = array('link' => '插件管理|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->editor    = array('link' => '扩展编辑器|editor|index', 'subModule' => 'editor');
$lang->admin->menu->mail      = array('link' => '发信配置|mail|index', 'subModule' => 'mail');
$lang->admin->menu->clearData = array('link' => '清除数据|admin|cleardata');
$lang->admin->menu->convert   = array('link' => '从其他系统导入|convert|index', 'subModule' => 'convert');
$lang->admin->menu->trashes   = array('link' => '回收站|action|trash', 'subModule' => 'action');
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

/*菜单设置：分组设置。*/
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

/* 模块顺序。*/
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

/* 方法顺序。*/
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

/* 错误提示信息。*/
$lang->error->companyNotFound = "您访问的域名 %s 没有对应的公司。";
$lang->error->length          = array("『%s』长度错误，应当为『%s』", "『%s』长度应当不超过『%s』，且不小于『%s』。");
$lang->error->reg             = "『%s』不符合格式，应当为:『%s』。";
$lang->error->unique          = "『%s』已经有『%s』这条记录了。";
$lang->error->gt              = "『%s』应当大于『%s』。";
$lang->error->ge              = "『%s』应当不小于『%s』。";
$lang->error->notempty        = "『%s』不能为空。";
$lang->error->empty           = "『%s』必须为空。";
$lang->error->equal           = "『%s』必须为『%s』。";
$lang->error->int             = array("『%s』应当是数字。", "『%s』应当介于『%s-%s』之间。");
$lang->error->float           = "『%s』应当是数字，可以是小数。";
$lang->error->email           = "『%s』应当为合法的EMAIL。";
$lang->error->date            = "『%s』应当为合法的日期。";
$lang->error->account         = "『%s』应当为合法的用户名。";
$lang->error->passwordsame    = "两次密码应当相等。";
$lang->error->passwordrule    = "密码应该符合规则，长度至少为六位。";
$lang->error->accessDenied    = '您没有访问权限';

/* 分页信息。*/
$lang->pager->noRecord  = "暂时没有记录";
$lang->pager->digest    = "共<strong>%s</strong>条记录，每页 <strong>%s</strong>条，<strong>%s/%s</strong> ";
$lang->pager->first     = "首页";
$lang->pager->pre       = "上页";
$lang->pager->next      = "下页";
$lang->pager->last      = "末页";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "官方网站";
$lang->chinaScrum     = "<a href='http://www.zentao.net/goto.php?item=chinascrum' target='_blank'>Scrum社区</a> ";
$lang->agileTraining  = "<a href='http://www.zentao.net/goto.php?item=agiletrain' target='_blank'>培训</a> ";
$lang->donate         = "<a href='http://www.zentao.net/goto.php?item=donate' target='_blank'>捐赠</a> ";

$lang->suhosinInfo = "警告：数据太多，请在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（设置更大的数）。 保存并重新启动apache，否则会造成部分数据无法保存。";

$lang->noResultsMatch = "没有匹配结果";

/* 时间格式设置。*/
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
