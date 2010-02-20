<?php
/**
 * The common english language file of ZenTaoMS.
 *
 * All items used commonly should be defined here.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->zentaoMS   = 'ZenTaoPMS';
$lang->logout     = 'Logout';
$lang->login      = 'Login';
$lang->currentPos = 'Current POS';
$lang->arrow      = '>>';
$lang->colon      = '::';
$lang->reset      = 'Reset';
$lang->edit       = 'Edit';
$lang->delete     = 'Delete';
$lang->close      = 'Close';
$lang->activate   = 'Activate';
$lang->delete     = 'Delete';
$lang->save       = 'Save';
$lang->actions    = 'Actions';
$lang->comment    = 'Comment';
$lang->history    = 'History';
$lang->welcome    = "Welcome to use %s{$lang->colon}{$lang->zentaoMS}";
$lang->zentaoSite = "Official Site";
$lang->myControl  = "Dashboard";
$lang->sponser    = "<a href='http://www.pujia.com' target='_blank'>PUJIA donated</a>";
$lang->at         = ' at ';
$lang->feature    = 'Feature';
$lang->year       = 'Year';
$lang->downArrow  = '↓';
$lang->goback     = 'Go Back';
$lang->selectAll  = '全选';
$lang->attatch    = '附件';
$lang->reverse    = '（切换顺序）';
$lang->addFiles   = '上传了附件 ';

/* 主导航菜单。*/
$lang->menu->index   = 'Home|index|index';
$lang->menu->my      = 'Dashboard|my|index';
$lang->menu->product = 'Product View|product|index';
$lang->menu->project = 'Project View|project|index';
$lang->menu->qa      = 'QA View|qa|index';
$lang->menu->company = 'Org View|company|index';
$lang->menu->admin   = 'Admin|admin|index';

/* 首页菜单设置。*/
$lang->index->menu->product = '浏览产品|product|browse';
$lang->index->menu->project = '浏览项目|project|browse';

/* 我的地盘菜单设置。*/
$lang->my->menu->account  = '%s' . $lang->arrow;
$lang->my->menu->todo     = array('link' => '我的TODO|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task     = '我的任务|my|task|';
$lang->my->menu->project  = '我的项目|my|project|';
$lang->my->menu->story    = '我的需求|my|story|';
$lang->my->menu->bug      = '我的Bug|my|bug|';
$lang->my->menu->profile  = array('link' => '我的档案|my|profile|', 'alias' => 'editprofile');
$lang->todo->menu         = $lang->my->menu;

/* 产品视图设置。*/
$lang->product->menu->list   = '%s';
$lang->product->menu->story  = array('link' => '需求列表|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->plan   = array('link' => '计划列表|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release= array('link' => '发布列表|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap= '路线图|product|roadmap|productID=%s';
$lang->product->menu->edit   = '编辑产品|product|edit|productID=%s';
$lang->product->menu->delete = array('link' => '删除产品|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->module = '维护模块|tree|browse|productID=%s&view=product';
$lang->product->menu->create = array('link' => '新增产品|product|create', 'float' => 'right');
$lang->story->menu           = $lang->product->menu;
$lang->productplan->menu     = $lang->product->menu;
$lang->release->menu         = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project->menu->list   = '%s';
$lang->project->menu->task   = array('link' => '任务列表|project|task|projectID=%s', 'subModule' => 'task');
$lang->project->menu->story  = array('link' => '需求列表|project|story|projectID=%s', 'alias' => 'linkstory');
$lang->project->menu->bug    = 'Bug列表|project|bug|projectID=%s';
$lang->project->menu->build  = array('link' => 'Build列表|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->burn   = '燃烧图|project|burn|projectID=%s';
$lang->project->menu->team   = array('link' => '团队成员|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->line   = $lang->colon;
$lang->project->menu->view   = '基本信息|project|view|projectID=%s';
$lang->project->menu->edit   = '编辑项目|project|edit|projectID=%s';
$lang->project->menu->delete = array('link' => '删除项目|project|delete|projectID=%s', 'target' => 'hiddenwin');
$lang->project->menu->product= '关联产品|project|manageproducts|projectID=%s';

$lang->project->menu->create = array('link' => '新增项目|project|create', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;
$lang->build->menu           = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,active', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,active');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');
$lang->testcase->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s');

$lang->testtask->menu->product  = '%s';
$lang->testtask->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,active');
$lang->testtask->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');
$lang->testtask->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');

/* 组织结构视图菜单设置。*/
$lang->company->menu->browseUser  = array('link' => '用户列表|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部门维护|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '权限分组|group|browse', 'subModule' => 'group');
$lang->company->menu->addGroup    = array('link' => '添加分组|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '添加用户|user|create|company=%s&dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;

/* 用户信息菜单设置。*/
$lang->user->menu->account  = '%s' . $lang->arrow;
$lang->user->menu->todo     = array('link' => 'TODO列表|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task     = '任务列表|user|task|account=%s';
$lang->user->menu->project  = '项目列表|user|project|account=%s';
$lang->user->menu->bug      = 'Bug列表|user|bug|account=%s';
$lang->user->menu->profile  = array('link' => '用户信息|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse   = array('link' => '用户管理|company|browse|', 'float' => 'right');

/* 后台管理菜单设置。*/
$lang->admin->menu->browseCompany = array('link' => '公司管理|admin|browsecompany', 'subModule' => 'company');
//$lang->admin->menu->convert       = array('link' => '从其他系统导入|convert|index', 'subModule' => 'convert');
$lang->admin->menu->upgrade       = array('link' => '升级|upgrade|index',           'subModule' => 'upgrade');
$lang->admin->menu->createCompany = array('link' => '新增公司|company|create', 'float' => 'right');
$lang->convert->menu              = $lang->admin->menu;
$lang->upgrade->menu              = $lang->admin->menu;

/*菜单设置：分组设置。*/
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->company     = 'admin';
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

/* 错误提示信息。*/
$lang->error->companyNotFound = "您访问的域名 %s 没有对应的公司。";
$lang->error->length          = array("『%s』长度错误，应当为『%s』", "『%s』长度应当不超过『%s』，且不小于『%s』。");
$lang->error->reg             = "『%s』不符合格式，应当为:『%s』。";
$lang->error->unique          = "『%s』已经有『%s』这条记录了。";
$lang->error->notempty        = "『%s』不能为空。";
$lang->error->int             = array("『%s』应当是数字。", "『%s』应当介于『%s-%s』之间。");
$lang->error->float           = "『%s』应当是数字，可以是小数。";
$lang->error->email           = "『%s』应当为合法的EMAIL。";
$lang->error->date            = "『%s』应当为合法的日期。";
$lang->error->account         = "『%s』应当为合法的用户名。";
$lang->error->passwordsame    = "两次密码应当相等。";
$lang->error->passwordrule    = "密码应该符合规则。";

/* 分页信息。*/
$lang->pager->noRecord  = "暂时没有记录";
$lang->pager->digest    = "共<strong>%s</strong>条记录,每页 <strong>%s</strong>条，页面：<strong>%s/%s</strong> ";
$lang->pager->first     = "首页";
$lang->pager->pre       = "上页";
$lang->pager->next      = "下页";
$lang->pager->last      = "末页";
$lang->pager->locate    = "GO!";
