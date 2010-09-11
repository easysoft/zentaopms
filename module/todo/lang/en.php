<?php
/**
 * The todo module English file of ZenTaoMS.
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
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->todo->common    = 'TODO';
$lang->todo->index     = "Index";
$lang->todo->create    = "Create";
$lang->todo->edit      = "Edit";
$lang->todo->view      = "Info";
$lang->todo->viewAB    = "Info";
$lang->todo->markDone  = "Undone";
$lang->todo->markWait  = "Done";
$lang->todo->markDoing = "Doing";
$lang->todo->mark      = "Change status";
$lang->todo->delete    = "Delete";
$lang->todo->browse    = "Browse";
$lang->todo->import2Today = "Import to today";
$lang->todo->changeStatus = "Change";

$lang->todo->id          = 'ID';
$lang->todo->account     = 'Owner';
$lang->todo->date        = 'Date';
$lang->todo->begin       = 'Begin time';
$lang->todo->beginAB     = 'Begin';
$lang->todo->end         = 'End time';
$lang->todo->endAB       = 'End';
$lang->todo->beginAndEnd = 'Begin and End';
$lang->todo->type        = 'Type';
$lang->todo->pri         = 'Priority';
$lang->todo->name        = 'Name';
$lang->todo->status      = 'Status';
$lang->todo->desc        = 'Desc';
$lang->todo->private     = 'Private';
$lang->todo->idvalue     = 'Task or bug';

$lang->todo->week  = '(l)';  // date function's param.
$lang->todo->today = 'Today';
$lang->todo->weekDateList = '';
$lang->todo->dayInFeature = 'Feature';
$lang->todo->confirmBug   = 'This todo linked to bug #%s，chang it also?';
$lang->todo->confirmTask  = 'This todo linked to task #%s，chang it also?';

$lang->todo->statusList['wait']     = 'Waiting';
$lang->todo->statusList['doing']    = 'Doing';
$lang->todo->statusList['done']     = 'Done';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '3';
$lang->todo->priList[1] = '1';
$lang->todo->priList[2] = '2';
$lang->todo->priList[4] = '4';

$lang->todo->typeList->custom = 'Custom';
$lang->todo->typeList->bug    = 'Bug';
$lang->todo->typeList->task   = 'Task';

$lang->todo->confirmDelete  = "Are you sure to delete this todo?";
$lang->todo->successMarked  = "Successfully changed status";;
$lang->todo->thisIsPrivate  = 'This is a private todo。:)';
$lang->todo->lblDisableDate = 'Set time lately';

$lang->todo->thisWeekTodos = 'This week';
$lang->todo->lastWeekTodos = 'Last week';
$lang->todo->allDaysTodos  = 'All todo';
$lang->todo->allUndone     = 'Undone';
$lang->todo->todayTodos    = 'Today';

$lang->todo->action->marked = array('main' => '$date, Change status to <stong>$extra</strong> by <strong>$actor</strong>。', 'extra' => $lang->todo->statusList);
