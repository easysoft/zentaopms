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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->action->desc->common    = '$date, <strong>$action</strong> by <strong>$actor</strong>';
$lang->action->desc->extra     = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>';
$lang->action->desc->opened    = '$date, 由 <strong>$actor</strong> 创建。';
$lang->action->desc->changed   = '$date, 由 <strong>$actor</strong> 变更。';
$lang->action->desc->edited    = '$date, 由 <strong>$actor</strong> 编辑。';
$lang->action->desc->closed    = '$date, 由 <strong>$actor</strong> 关闭。';
$lang->action->desc->commented = '$date, 由 <strong>$actor</strong> 发表评论。';
$lang->action->desc->activated = '$date, 由 <strong>$actor</strong> 激活。';
$lang->action->desc->moved     = '$date, 由 <strong>$actor</strong> 移动，之前为 "$extra"';
$lang->action->desc->confirmed = '$date, 由 <strong>$actor</strong> 确认需求变动，最新版本为<strong>#$extra</strong>';
$lang->action->desc->diff1     = '修改了 <strong><i>%s</i></strong>，旧值为 "%s"，新值为 "%s"。<br />';
$lang->action->desc->diff2     = '修改了 <strong><i>%s</i></strong>，区别为：<blockquote>%s</blockquote>';

$lang->action->label->opened    = '创建了';
$lang->action->label->changed   = '变更了';
$lang->action->label->edited    = '编辑了';
$lang->action->label->closed    = '关闭了';
$lang->action->label->commented = '评论了';
$lang->action->label->activated = '激活了';
$lang->action->label->resolved  = '解决了';
$lang->action->label->reviewed  = '评审了';
$lang->action->label->moved     = '移动了';
$lang->action->label->confirmed = '确认了需求，';
$lang->action->label->story     = '需求|story|view|storyID=%s';
$lang->action->label->task      = '任务|task|view|taskID=%s';
$lang->action->label->bug       = 'Bug|bug|view|bugID=%s';
$lang->action->label->case      = '用例|testcase|view|caseID=%s';
$lang->action->label->space     = '　';
