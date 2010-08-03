<?php
/**
 * The action module English file of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
$lang->action->common   = 'Logs';
$lang->action->trash    = 'Trash';
$lang->action->undelete = 'Restore';

$lang->action->objectType = 'Object';
$lang->action->objectID   = 'ID';
$lang->action->objectName = 'Name';
$lang->action->actor      = 'Actor';
$lang->action->date       = 'Date';

$lang->action->objectTypes['product']     = 'PRODUCT';
$lang->action->objectTypes['story']       = 'STORY';
$lang->action->objectTypes['productplan'] = 'PLAN';
$lang->action->objectTypes['release']     = 'RELEASE';
$lang->action->objectTypes['project']     = 'PROJECT';
$lang->action->objectTypes['task']        = 'TASK';
$lang->action->objectTypes['build']       = 'Build';
$lang->action->objectTypes['bug']         = 'Bug';
$lang->action->objectTypes['case']        = 'Case';
$lang->action->objectTypes['testtask']    = 'Test Task';
$lang->action->objectTypes['user']        = 'User';
$lang->action->objectTypes['doc']         = 'DOC';
$lang->action->objectTypes['doclib']      = 'DocLib';

/* 用来描述操作历史记录.*/
$lang->action->desc->common    = '$date, <strong>$action</strong> by <strong>$actor</strong>';
$lang->action->desc->extra     = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>';
$lang->action->desc->opened    = '$date, Opened by <strong>$actor</strong>.';
$lang->action->desc->created   = '$date, Created by <strong>$actor</strong>.';
$lang->action->desc->changed   = '$date, Changed by <strong>$actor</strong>.';
$lang->action->desc->edited    = '$date, Edited by <strong>$actor</strong>.';
$lang->action->desc->closed    = '$date, Closed by <strong>$actor</strong>.';
$lang->action->desc->deleted   = '$date, Deleted by <strong>$actor</strong>.';
$lang->action->desc->erased    = '$date, Erased by <strong>$actor</strong>.';
$lang->action->desc->undeleted = '$date, Restored by <strong>$actor</strong>.';
$lang->action->desc->commented = '$date, Commented by <strong>$actor</strong>.';
$lang->action->desc->activated = '$date, Activated by <strong>$actor</strong>.';
$lang->action->desc->moved     = '$date, Moved by <strong>$actor</strong>, previouse is "$extra"';
$lang->action->desc->confirmed = '$date, Confirmed by <strong>$actor</strong>, version is<strong>#$extra</strong>';
$lang->action->desc->started   = '$date, Started by <strong>$actor</strong>.';
$lang->action->desc->canceled  = '$date, Canceled by <strong>$actor</strong>.';
$lang->action->desc->finished  = '$date, Finished by <strong>$actor</strong>.';
$lang->action->desc->diff1     = 'Changed <strong><i>%s</i></strong>, old is "%s", new is "%s".<br />';
$lang->action->desc->diff2     = 'Changed <strong><i>%s</i></strong>, the diff is：<blockquote>%s</blockquote>';

/* 用来显示动态信息.*/
$lang->action->label->opened              = 'opened';
$lang->action->label->created             = 'created';
$lang->action->label->changed             = 'changed';
$lang->action->label->edited              = 'edited';
$lang->action->label->closed              = 'closed';
$lang->action->label->deleted             = 'deleted';
$lang->action->label->erased              = 'deleted';
$lang->action->label->undeleted           = 'restore';
$lang->action->label->commented           = 'commented';
$lang->action->label->activated           = 'activated';
$lang->action->label->resolved            = 'resoved';
$lang->action->label->reviewed            = 'reviewed';
$lang->action->label->moved               = 'moded';
$lang->action->label->confirmed           = 'confirmed,';
$lang->action->label->linked2plan         = 'link to plan';
$lang->action->label->unlinkedfromplan    = 'unlink from plan';
$lang->action->label->linked2project      = 'link to project';
$lang->action->label->unlinkedfromproject = 'unlik from project';
$lang->action->label->marked              = 'edited';
$lang->action->label->started             = 'started';
$lang->action->label->canceled            = 'ccanceled';
$lang->action->label->finished            = 'finished';
$lang->action->label->login               = 'login';
$lang->action->label->logout              = "logout";

/* 用来生成相应对象的链接.*/
$lang->action->label->product     = 'product|product|view|productID=%s';
$lang->action->label->productplan = 'plan|productplan|view|productID=%s';
$lang->action->label->release     = 'release|release|view|productID=%s';
$lang->action->label->story       = 'story|story|view|storyID=%s';
$lang->action->label->project     = 'project|project|view|projectID=%s';
$lang->action->label->task        = 'task|task|view|taskID=%s';
$lang->action->label->build       = 'build|build|view|buildID=%s';
$lang->action->label->bug         = 'bug|bug|view|bugID=%s';
$lang->action->label->case        = 'case|testcase|view|caseID=%s';
$lang->action->label->testtask    = 'test task|testtask|view|caseID=%s';
$lang->action->label->todo        = 'todo|todo|view|todoID=%s';
$lang->action->label->doclib      = 'doc library|doc|browse|libID=%s';
$lang->action->label->doc         = 'doc|doc|view|docID=%s';
$lang->action->label->user        = 'user';

$lang->action->label->space     = ' ';
