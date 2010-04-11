<?php
/**
 * The action module zh-cn file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
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
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->action->common   = '系统日志';
$lang->action->trash    = '回收站';
$lang->action->undelete = '还原';

$lang->action->objectType = '对象类型';
$lang->action->objectID   = '对象ID';
$lang->action->objectName = '对象名称';
$lang->action->actor      = '操作者';
$lang->action->date       = '日期';

$lang->action->objectTypes['company']     = '公司';
$lang->action->objectTypes['product']     = '产品';
$lang->action->objectTypes['story']       = '需求';
$lang->action->objectTypes['productplan'] = '产品计划';
$lang->action->objectTypes['release']     = '发布';
$lang->action->objectTypes['project']     = '项目';
$lang->action->objectTypes['task']        = '任务';
$lang->action->objectTypes['build']       = 'Build';
$lang->action->objectTypes['bug']         = 'Bug';
$lang->action->objectTypes['case']        = '用例';
$lang->action->objectTypes['testtask']    = '测试任务';

/* 用来描述操作历史记录。*/
$lang->action->desc->common    = '$date, <strong>$action</strong> by <strong>$actor</strong>';
$lang->action->desc->extra     = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>';
$lang->action->desc->opened    = '$date, 由 <strong>$actor</strong> 创建。';
$lang->action->desc->changed   = '$date, 由 <strong>$actor</strong> 变更。';
$lang->action->desc->edited    = '$date, 由 <strong>$actor</strong> 编辑。';
$lang->action->desc->closed    = '$date, 由 <strong>$actor</strong> 关闭。';
$lang->action->desc->deleted   = '$date, 由 <strong>$actor</strong> 删除。';
$lang->action->desc->undeleted = '$date, 由 <strong>$actor</strong> 还原。';
$lang->action->desc->commented = '$date, 由 <strong>$actor</strong> 发表评论。';
$lang->action->desc->activated = '$date, 由 <strong>$actor</strong> 激活。';
$lang->action->desc->moved     = '$date, 由 <strong>$actor</strong> 移动，之前为 "$extra"';
$lang->action->desc->confirmed = '$date, 由 <strong>$actor</strong> 确认需求变动，最新版本为<strong>#$extra</strong>';
$lang->action->desc->diff1     = '修改了 <strong><i>%s</i></strong>，旧值为 "%s"，新值为 "%s"。<br />';
$lang->action->desc->diff2     = '修改了 <strong><i>%s</i></strong>，区别为：<blockquote>%s</blockquote>';

/* 用来显示动态信息。*/
$lang->action->label->opened           = '创建了';
$lang->action->label->changed          = '变更了';
$lang->action->label->edited           = '编辑了';
$lang->action->label->closed           = '关闭了';
$lang->action->label->deleted          = '删除了';
$lang->action->label->undeleted        = '还原了';
$lang->action->label->commented        = '评论了';
$lang->action->label->activated        = '激活了';
$lang->action->label->resolved         = '解决了';
$lang->action->label->reviewed         = '评审了';
$lang->action->label->moved            = '移动了';
$lang->action->label->confirmed        = '确认了需求，';
$lang->action->label->linked2plan      = '关联计划';
$lang->action->label->unlinkedfromplan = '移除计划';
$lang->action->label->linked2prj       = '关联项目';
$lang->action->label->unlinkedfromprj  = '移除项目';
$lang->action->label->login            = '登录系统';
$lang->action->label->logout           = "退出登录";

/* 用来生成相应对象的链接。*/
$lang->action->label->product     = '产品|product|view|productID=%s';
$lang->action->label->productplan = '计划|productplan|view|productID=%s';
$lang->action->label->release     = '发布|release|view|productID=%s';
$lang->action->label->story       = '需求|story|view|storyID=%s';
$lang->action->label->project     = '需求|story|view|storyID=%s';
$lang->action->label->task        = '任务|task|view|taskID=%s';
$lang->action->label->build       = 'Build|build|view|buildID=%s';
$lang->action->label->bug         = 'Bug|bug|view|bugID=%s';
$lang->action->label->case        = '用例|testcase|view|caseID=%s';
$lang->action->label->testtask    = '测试任务|testtask|view|caseID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';

$lang->action->label->space     = '　';
