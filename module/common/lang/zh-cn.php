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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoMS
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->arrow        = ' » ';
$lang->colon        = '::';
$lang->comma        = '，';
$lang->dot          = '。';
$lang->at           = ' 于 ';
$lang->downArrow    = '↓';

$lang->zentaoMS     = '禅道管理';
$lang->welcome      = "欢迎使用『%s』{$lang->colon} {$lang->zentaoMS}";
$lang->myControl    = "我的地盘";
$lang->currentPos   = '当前位置：';
$lang->logout       = '退出系统';
$lang->login        = '登录';
$lang->aboutZenTao  = '关于禅道';
$lang->todayIs      = '今天是%s，';

$lang->reset        = '重填';
$lang->edit         = '编辑';
$lang->copy         = '复制';
$lang->delete       = '删除';
$lang->close        = '关闭';
$lang->link         = '关联';
$lang->unlink       = '移除';
$lang->import       = '导入';
$lang->exportCSV    = '导出csv';
$lang->setFileName  = '请输入文件名：';
$lang->activate     = '激活';
$lang->save         = '保存';
$lang->confirm      = '确认';
$lang->preview      = '预览';
$lang->goback       = '返回';
$lang->go           = 'GO!';
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

$lang->selectAll    = '全选';
$lang->notFound     = '抱歉，您访问的对象并不存在！';
$lang->showAll      = '++ 全部显示 ++';
$lang->hideClosed   = '-- 隐藏已结束 --';

$lang->feature      = '未来';
$lang->year         = '年';
$lang->workingHour  = '工时';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '状态';
$lang->openedByAB   = '创建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '类型';

/* 主导航菜单。*/
$lang->menu->index   = '首页|index|index';
$lang->menu->my      = '我的地盘|my|index';
$lang->menu->product = '产品视图|product|index';
$lang->menu->project = '项目视图|project|index';
$lang->menu->qa      = 'QA视图|qa|index';
$lang->menu->doc     = '文档视图|doc|index';
//$lang->menu->forum   = '讨论视图|doc|index';
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
$lang->searchObjects['build']       = 'B:Build';
$lang->searchObjects['release']     = 'R:发布';
$lang->searchObjects['productplan'] = 'P:产品计划';
$lang->searchObjects['testtask']    = 'T:测试任务';
$lang->searchTips                   = '输入编号';

/* 首页菜单设置。*/
$lang->index->menu->product = '浏览产品|product|browse';
$lang->index->menu->project = '浏览项目|project|browse';

/* 我的地盘菜单设置。*/
$lang->my->menu->account  = '%s' . $lang->arrow;
$lang->my->menu->todo     = array('link' => '我的TODO|my|todo|', 'subModule' => 'todo');
$lang->my->menu->task     = '我的任务|my|task|';
$lang->my->menu->bug      = '我的Bug|my|bug|';
$lang->my->menu->story    = '我的需求|my|story|';
$lang->my->menu->project  = '我的项目|my|project|';
$lang->my->menu->profile  = array('link' => '我的档案|my|profile|', 'alias' => 'editprofile');
$lang->todo->menu         = $lang->my->menu;

/* 产品视图设置。*/
$lang->product->menu->list   = '%s';
$lang->product->menu->story  = array('link' => '需求列表|product|browse|productID=%s',     'subModule' => 'story');
$lang->product->menu->plan   = array('link' => '计划列表|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release= array('link' => '发布列表|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap= '路线图|product|roadmap|productID=%s';
$lang->product->menu->doc    = array('link' => '文档列表|product|doc|productID=%s', 'subModule' => 'doc');
$lang->product->menu->view   = '基本信息|product|view|productID=%s';
$lang->product->menu->edit   = '编辑产品|product|edit|productID=%s';
$lang->product->menu->module = '维护模块|tree|browse|productID=%s&view=story';
$lang->product->menu->delete = array('link' => '删除产品|product|delete|productID=%s', 'target' => 'hiddenwin');
$lang->product->menu->create = array('link' => '新增产品|product|create', 'float' => 'right');
$lang->story->menu           = $lang->product->menu;
$lang->productplan->menu     = $lang->product->menu;
$lang->release->menu         = $lang->product->menu;

/* 项目视图菜单设置。*/
$lang->project->menu->list      = '%s';
$lang->project->menu->task      = array('link' => '任务|project|task|projectID=%s', 'subModule' => 'task', 'alias' => 'grouptask,importtask');
$lang->project->menu->story     = array('link' => '需求|project|story|projectID=%s');
$lang->project->menu->bug       = 'Bug|project|bug|projectID=%s';
$lang->project->menu->build     = array('link' => 'Build|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->menu->burn      = '燃尽图|project|burn|projectID=%s';
$lang->project->menu->team      = array('link' => '团队|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->doc       = array('link' => '文档|project|doc|porjectID=%s', 'subModule' => 'doc');
$lang->project->menu->product   = '关联产品|project|manageproducts|projectID=%s';
$lang->project->menu->linkstory = array('link' => '关联需求|project|linkstory|projectID=%s');
$lang->project->menu->view      = '基本信息|project|view|projectID=%s';
$lang->project->menu->edit      = '编辑项目|project|edit|projectID=%s';
$lang->project->menu->delete    = array('link' => '删除项目|project|delete|projectID=%s', 'target' => 'hiddenwin');

$lang->project->menu->create = array('link' => '新增项目|project|create', 'float' => 'right');
$lang->task->menu            = $lang->project->menu;
$lang->build->menu           = $lang->project->menu;

/* QA视图菜单设置。*/
$lang->bug->menu->product  = '%s';
$lang->bug->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s', 'alias' => 'view,create,edit,resolve,close,activate,report', 'subModule' => 'tree');
$lang->bug->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->bug->menu->testtask = array('link' => '测试任务|testtask|browse|productID=%s');

$lang->testcase->menu->product  = '%s';
$lang->testcase->menu->bug      = array('link' => '缺陷管理|bug|browse|productID=%s');
$lang->testcase->menu->testcase = array('link' => '用例管理|testcase|browse|productID=%s', 'alias' => 'view,create,edit', 'subModule' => 'tree');
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
$lang->doc->menu->create  = array('link' => '新增文档库|doc|createLib', 'float' => 'right');

/* 组织结构视图菜单设置。*/
$lang->company->menu->name        = '%s' . $lang->arrow;
$lang->company->menu->browseUser  = array('link' => '用户列表|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => '部门维护|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => '权限分组|group|browse', 'subModule' => 'group');
$lang->company->menu->edit        = array('link' => '公司管理|company|edit');
$lang->company->menu->addGroup    = array('link' => '添加分组|group|create', 'float' => 'right');
$lang->company->menu->addUser     = array('link' => '添加用户|user|create|dept=%s&from=company', 'subModule' => 'user', 'float' => 'right');
$lang->dept->menu            = $lang->company->menu;
$lang->group->menu           = $lang->company->menu;

/* 用户信息菜单设置。*/
$lang->user->menu->account  = '%s' . $lang->arrow;
$lang->user->menu->todo     = array('link' => 'TODO列表|user|todo|account=%s', 'subModule' => 'todo');
$lang->user->menu->task     = '任务列表|user|task|account=%s';
$lang->user->menu->bug      = 'Bug列表|user|bug|account=%s';
$lang->user->menu->project  = '项目列表|user|project|account=%s';
$lang->user->menu->profile  = array('link' => '用户信息|user|profile|account=%s', 'alias' => 'edit');
$lang->user->menu->browse   = array('link' => '用户管理|company|browse|', 'float' => 'right');

/* 后台管理菜单设置。*/
$lang->admin->menu->trashes = array('link' => '回收站|action|trash', 'subModule' => 'action');
$lang->admin->menu->convert = array('link' => '从其他系统导入|convert|index', 'subModule' => 'convert');
$lang->convert->menu        = $lang->admin->menu;
$lang->upgrade->menu        = $lang->admin->menu;
$lang->action->menu         = $lang->admin->menu;

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

/* 错误提示信息。*/
$lang->error->companyNotFound = "您访问的域名 %s 没有对应的公司。";
$lang->error->length          = array("『%s』长度错误，应当为『%s』", "『%s』长度应当不超过『%s』，且不小于『%s』。");
$lang->error->reg             = "『%s』不符合格式，应当为:『%s』。";
$lang->error->unique          = "『%s』已经有『%s』这条记录了。";
$lang->error->notempty        = "『%s』不能为空。";
$lang->error->equal           = "『%s』必须为『%s』。";
$lang->error->int             = array("『%s』应当是数字。", "『%s』应当介于『%s-%s』之间。");
$lang->error->float           = "『%s』应当是数字，可以是小数。";
$lang->error->email           = "『%s』应当为合法的EMAIL。";
$lang->error->date            = "『%s』应当为合法的日期。";
$lang->error->account         = "『%s』应当为合法的用户名。";
$lang->error->passwordsame    = "两次密码应当相等。";
$lang->error->passwordrule    = "密码应该符合规则，长度至少为六位。";

/* 分页信息。*/
$lang->pager->noRecord  = "暂时没有记录";
$lang->pager->digest    = "共<strong>%s</strong>条记录,每页 <strong>%s</strong>条，页面：<strong>%s/%s</strong> ";
$lang->pager->first     = "首页";
$lang->pager->pre       = "上页";
$lang->pager->next      = "下页";
$lang->pager->last      = "末页";
$lang->pager->locate    = "GO!";

$lang->zentaoSite     = "官方网站";
$lang->sponser        = "<a href='http://www.pujia.com' target='_blank'>普加赞助</a>";
$lang->zentaoKeywords = "开源项目管理软件,项目管理,项目管理软件,pmp,pms,php框架，国产php框架，scrum工具,scrum管理工具,scrum管理软件,敏捷项目管理,禅道";
$lang->zentaoDESC     = "禅道项目管理软件(ZenTaoPMS)是一款国产的，基于LGPL协议，开源免费的项目管理软件(工具、系统)，同时也是一款scrum管理工具。
    它集产品管理、项目管理、测试管理于一体，同时还包含了事务管理、组织管理等诸多功能，是中小型企业项目管理的首选。禅道项目管理软件使用PHP + MySQL开发，
基于自主的PHP开发框架──ZenTaoPHP而成。第三方开发者或者企业可以非常方便的开发插件或者进行定制。禅道在手，项目无忧！"; 

/* 时间格式设置。*/
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
$lang->smilies->sad         = '悲伤';
$lang->smilies->wink        = '眨眼';
$lang->smilies->tongue      = '吐舌头';
$lang->smilies->shocked     = '惊讶';
$lang->smilies->eyesdown    = '失望';
$lang->smilies->angry       = '愤怒';
$lang->smilies->cool        = '耍酷';
$lang->smilies->indifferent = '冷漠';
$lang->smilies->sick        = '病中';
$lang->smilies->blush       = '脸红';
$lang->smilies->angel       = '天使';
$lang->smilies->confused    = '困惑';
$lang->smilies->cry         = '大哭';
$lang->smilies->footinmouth = '保密';
$lang->smilies->biggrin     = '大笑';
$lang->smilies->nerd        = '书呆子';
$lang->smilies->tired       = '好累';
$lang->smilies->rose        = '玫瑰';
$lang->smilies->kiss        = '吻';
$lang->smilies->heart       = '心';
$lang->smilies->hug         = '拥抱';
$lang->smilies->dog         = '狗狗';
$lang->smilies->deadrose    = '残花';
$lang->smilies->clock       = '时钟';
$lang->smilies->brokenheart = '伤心';
$lang->smilies->coffee      = '咖啡';
$lang->smilies->computer    = '计算机';
$lang->smilies->devil       = '魔鬼';
$lang->smilies->thumbsup    = '赞同';
$lang->smilies->thumbsdown  = '反对';
$lang->smilies->mail        = '邮件';
