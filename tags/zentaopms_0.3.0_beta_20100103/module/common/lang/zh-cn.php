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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->zentaoMS   = '禅道管理';
$lang->logout     = '退出系统';
$lang->login      = '登陆';
$lang->currentPos = '当前位置：';
$lang->arrow      = ' » ';
$lang->colon      = '::';
$lang->reset      = '重填';
$lang->edit       = '编辑';
$lang->delete     = '删除';
$lang->save       = '保存';
$lang->action     = '操作';
$lang->comment    = '备注';
$lang->history    = '历史记录';
$lang->welcome    = "欢迎使用%s{$lang->colon}{$lang->zentaoMS}";
$lang->zentaoSite = "官方网站";
$lang->myControl  = "我的地盘";
$lang->sponser    = "<a href='http://www.pujia.com' target='_blank'>普加赞助</a>";
$lang->at         = ' 于 ';

/* 主导航菜单。*/
$lang->menu->index   = '首页|index|index';
$lang->menu->my      = '我的地盘|my|index';
$lang->menu->product = '产品视图|product|index';
$lang->menu->project = '项目视图|project|index';
$lang->menu->qa      = 'QA视图|qa|index';
$lang->menu->company = '组织视图|company|index';
$lang->menu->admin   = '后台管理|admin|index';

/* 首页菜单设置。*/
$lang->index->menu->product = '浏览产品|product|browse';
$lang->index->menu->project = '浏览项目|project|browse';

/* 我的地盘菜单设置。*/
$lang->my->menu->account  = '%s' . $lang->arrow;
$lang->my->menu->todo     = array('link' => '我的TODO|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task     = '我的任务|my|task|';
$lang->my->menu->project  = '我的项目|my|project|';
$lang->my->menu->bug      = '我的Bug|my|bug|';
$lang->my->menu->profile  = array('link' => '我的档案|my|profile|', 'alias' => 'editprofile');
$lang->todo->menu         = $lang->my->menu;

/* 产品视图设置。*/
$lang->product->menu->list   = '%s';
$lang->product->menu->story  = array('link' => '需求列表|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->plan   = array('link' => '计划列表|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->edit   = '编辑产品|product|edit|productID=%s';
$lang->product->menu->delete = array('link' => '删除产品|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->module = '维护模块|tree|browse|productID=%s&view=product';
$lang->product->menu->create = array('link' => '新增产品|product|create', 'float' => 'right');
$lang->story->menu           = $lang->product->menu;
$lang->productplan->menu     = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project->menu->list   = '%s';
$lang->project->menu->task   = array('link' => '任务列表|project|task|projectID=%s', 'subModule' => 'task');
$lang->project->menu->story  = array('link' => '需求列表|project|story|projectID=%s', 'alias' => 'linkstory');
$lang->project->menu->bug    = 'Bug列表|project|bug|projectID=%s';
$lang->project->menu->burn   = '燃烧图|project|burn|projectID=%s';
$lang->project->menu->team   = array('link' => '团队成员|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->line   = $lang->colon;
$lang->project->menu->view   = '基本信息|project|view|projectID=%s';
$lang->project->menu->edit   = '编辑项目|project|edit|projectID=%s';
$lang->project->menu->delete = array('link' => '删除项目|project|delete|projectID=%s', 'target' => 'hiddenwin');
$lang->project->menu->product= '关联产品|project|manageproducts|projectID=%s';

$lang->project->menu->create = array('link' => '新增项目|project|create', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,active', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,active');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');

/* 组织结构视图菜单设置。*/
$lang->company->menu->browseUser  = array('link' => '用户管理|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部门结构|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '分组管理|group|browse', 'subModule' => 'group');
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
$lang->admin->menu->createCompany = array('link' => '新增公司|company|create', 'float' => 'right');

/*菜单设置：分组设置。*/
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->company     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
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
