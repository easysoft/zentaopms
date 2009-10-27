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
$lang->zentaoMS   = '禅道项目管理系统';
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

/* 菜单设置：顶级的tab。*/
$lang->menu->index   = '首页|index|index';
$lang->menu->my      = '我的地盘|my|index';
$lang->menu->product = '产品视图|product|index';
$lang->menu->project = '项目视图|project|index';
$lang->menu->qa      = 'QA视图|qa|index';
$lang->menu->company = '组织视图|company|index';
//$lang->menu->misc    = '其他相关|misc|index';
$lang->menu->admin   = '后台管理|admin|index';

/*菜单设置：下级菜单。*/
$lang->submenu->index->item1 = '浏览产品|product|browse';
$lang->submenu->index->item2 = '浏览项目|project|browse';

$lang->submenu->product->item1 = '浏览产品|product|index';
$lang->submenu->product->item2 = '新增产品|product|create';

$lang->submenu->project ->item1 = '新增项目|project|create';
$lang->submenu->project ->item2 = '浏览项目|project|browse';

$lang->submenu->qa->item1      = '缺陷管理|bug|index';
//$lang->submenu->qa->item2      = '用例管理|testcase|index';

$lang->submenu->my->item1 = '我的TODO|my|todo';
$lang->submenu->my->item2 = '我的任务|my|task';
$lang->submenu->my->item3 = '我的项目|my|project';
$lang->submenu->my->item4 = '我的Bug|my|bug';
$lang->submenu->my->item6 = '我的档案|my|editprofile';

$lang->submenu->company->item1 = '组织结构|company|index';

$lang->submenu->admin->item1 = '浏览公司|admin|browsecompany';
$lang->submenu->admin->item2 = '新增公司|company|create';
$lang->submenu->admin->item3 = '|';
$lang->submenu->admin->item4 = '浏览分组|admin|browsegroup';
$lang->submenu->admin->item5 = '新增分组|group|create';
$lang->submenu->admin->item6 = '|';
$lang->submenu->admin->item7 = '浏览用户|admin|browseuser';
$lang->submenu->admin->item8 = '新增用户|user|create';

/*菜单设置：分组设置。*/
$lang->menugroup->release = 'product';
$lang->menugroup->story   = 'product';
$lang->menugroup->task    = 'project';
$lang->menugroup->company = 'admin';
$lang->menugroup->user    = 'admin';
$lang->menugroup->group   = 'admin';
$lang->menugroup->bug     = 'qa';
$lang->menugroup->testcase= 'qa';
$lang->menugroup->people  = 'company';
$lang->menugroup->dept    = 'company';
$lang->menugroup->todo    = 'my';

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

/* 分页信息。*/
$lang->pager->noRecord  = "暂时没有记录";
$lang->pager->digest    = "共<strong>%s</strong>条记录,每页 <strong>%s</strong>条，页面：<strong>%s/%s</strong> ";
$lang->pager->first     = "首页";
$lang->pager->pre       = "上页";
$lang->pager->next      = "下页";
$lang->pager->last      = "末页";
$lang->pager->locate    = "GO!";
