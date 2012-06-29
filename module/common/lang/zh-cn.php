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

/* 产品视图设置。*/
$lang->product->menu->list    = '%s';
$lang->product->menu->story   = array('link' => '需求|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->dynamic = '动态|product|dynamic|productID=%s';
$lang->product->menu->plan    = array('link' => '计划|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release = array('link' => '发布|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap = '路线图|product|roadmap|productID=%s';
$lang->product->menu->doc     = array('link' => '文档|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view    = '概况|product|view|productID=%s';
$lang->product->menu->edit    = '编辑|product|edit|productID=%s';
$lang->product->menu->delete  = array('link' => '删除|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->module  = '模块|tree|browse|productID=%s&view=story';
$lang->product->menu->project = '项目列表|product|project|status=all&productID=%s';
$lang->product->menu->order   = '排序|product|order|productID=%s';
$lang->product->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增产品|product|create', 'float' => 'right');
$lang->product->menu->all     = array('link' => '<span class="icon-all">&nbsp;</span>所有产品|product|index|locate=false', 'float' => 'right');
$lang->story->menu            = $lang->product->menu;
$lang->productplan->menu      = $lang->product->menu;
$lang->release->menu          = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '任务|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s', 'subModule' => 'story');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->dynamic   = '动态|project|dynamic|projectID=%s';
$lang->project->menu->build     = array('link' => '版本|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->testtask  = '测试申请|project|testtask|projectID=%s';
$lang->project->menu->burn      = '燃尽图|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => '团队|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文档|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '产品|project|manageproducts|projectID=%s';
$lang->project->menu->linkstory = array('link' => '关联需求|project|linkstory|projectID=%s');
$lang->project->menu->view      = '概况|project|view|projectID=%s';
$lang->project->menu->edit      = '编辑|project|edit|projectID=%s';
$lang->project->menu->delete    = array('link' => '删除|project|delete|projectID=%s', 'target' => 'hiddenwin');
$lang->project->menu->order     = '排序|project|order|projectID=%s';
$lang->project->menu->create    = array('link' => '<span class="icon-add1">&nbsp;</span>新增项目|project|create', 'float' => 'right');
$lang->project->menu->copy      = array('link' => '<span class="icon-copy">&nbsp;</span>复制项目|project|create|projectID=&copyProjectID=%s', 'float' => 'right');
$lang->project->menu->all       = array('link' => '<span class="icon-all">&nbsp;</span>所有项目|project|index|locate=false', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;
$lang->build->menu           = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s');

$lang->testtask->menu->product  = '%s';
$lang->testtask->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testtask->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s');
$lang->testtask->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases');

/* 文档视图菜单设置。*/
$lang->doc->menu->list    = '%s';
$lang->doc->menu->browse  = array('link' => '文档列表|doc|browse|libID=%s');
$lang->doc->menu->edit    = '编辑文档库|doc|editLib|libID=%s';
$lang->doc->menu->module  = '维护模块|tree|browse|libID=%s&viewType=doc';
$lang->doc->menu->delete  = array('link' => '删除文档库|doc|deleteLib|libID=%s', 'target' => 'hiddenwin');
$lang->doc->menu->create  = array('link' => '<span class="icon-add1">&nbsp;</span>新增文档库|doc|createLib', 'float' => 'right');

/* 统计视图菜单设置。*/
$lang->report->menu->prj     = array('link' => '项目|report|projectdeviation');
$lang->report->menu->product = array('link' => '产品|report|productinfo');
$lang->report->menu->test    = array('link' => '测试|report|bugsummary');
$lang->report->menu->staff   = array('link' => '员工|report|workload');

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

/* 用户信息菜单设置。*/
$lang->user->menu->account     = '%s' . $lang->arrow;
$lang->user->menu->todo        = array('link' => 'TODO列表|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task        = '任务列表|user|task|account=%s';
$lang->user->menu->bug         = 'Bug列表|user|bug|account=%s';
$lang->user->menu->dynamic     = '用户动态|user|dynamic|type=today&account=%s';
$lang->user->menu->projectList = '项目列表|user|project|account=%s';
$lang->user->menu->profile     = array('link' => '用户信息|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse      = array('link' => '<span class="icon-title">&nbsp;</span>用户管理|company|browse|', 'float' => 'right');

/* 后台管理菜单设置。*/
$lang->admin->menu->index     = array('link' => '首页|admin|index');
$lang->admin->menu->extension = array('link' => '插件管理|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->editor    = array('link' => '扩展编辑器|editor|index', 'subModule' => 'editor');
$lang->admin->menu->mail      = array('link' => '发信配置|mail|index', 'subModule' => 'mail');
$lang->admin->menu->clearData = array('link' => '清除数据|admin|cleardata');
$lang->admin->menu->convert   = array('link' => '从其他系统导入|convert|index', 'subModule' => 'convert');
$lang->admin->menu->trashes   = array('link' => '回收站|action|trash', 'subModule' => 'action');
$lang->convert->menu        = $lang->admin->menu;
$lang->upgrade->menu        = $lang->admin->menu;
$lang->action->menu         = $lang->admin->menu;
$lang->extension->menu      = $lang->admin->menu;
$lang->editor->menu         = $lang->admin->menu;
$lang->mail->menu           = $lang->admin->menu;

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
$lang->pager->digest    = "共<strong>%s</strong>条记录，每页 <strong>%s</strong>条，页面：<strong>%s/%s</strong> ";
$lang->pager->first     = "首页";
$lang->pager->pre       = "上页";
$lang->pager->next      = "下页";
$lang->pager->last      = "末页";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "官方网站";
$lang->chinaScrum     = "<a href='http://www.zentao.net/goto.php?item=chinascrum' target='_blank'>Scrum社区</a> ";
$lang->agileTraining  = "<a href='http://www.zentao.net/goto.php?item=agiletrain' target='_blank'>培训</a> ";
$lang->donate         = "<a href='http://www.zentao.net/goto.php?item=donate' target='_blank'>捐助禅道</a> ";
$lang->zentaoKeywords = "开源项目管理软件,项目管理,项目管理软件,pmp,pms,php框架，国产php框架，scrum工具,scrum管理工具,scrum管理软件,敏捷项目管理,禅道";
$lang->zentaoDESC     = "禅道项目管理软件(ZenTaoPMS)是一款国产的，基于LGPL协议，开源免费的项目管理软件(工具、系统)，同时也是一款scrum管理工具。
    它集产品管理、项目管理、测试管理于一体，同时还包含了事务管理、组织管理等诸多功能，是中小型企业项目管理的首选。禅道项目管理软件使用PHP + MySQL开发，
基于自主的PHP开发框架──ZenTaoPHP而成。第三方开发者或者企业可以非常方便的开发插件或者进行定制。禅道在手，项目无忧！"; 

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
