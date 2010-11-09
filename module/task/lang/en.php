<?php
/**
 * The task module English file of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->task->index     = "Index";
$lang->task->create    = "Create";
$lang->task->import    = "Import undone";
$lang->task->edit      = "Update";
$lang->task->delete    = "Delete";
$lang->task->view      = "Info";
$lang->task->logEfforts= "Efforts";
$lang->task->start     = "Start";
$lang->task->complete  = "Complete";
$lang->task->close     = "Close";
$lang->task->cancel    = "Cancel";
$lang->task->activate  = "Activate";
$lang->task->confirmStoryChange = "Confirm story change";

$lang->task->common       = 'Task';
$lang->task->id           = 'ID';
$lang->task->project      = 'Project';
$lang->task->story        = 'Story';
$lang->task->storyVersion = 'Version';
$lang->task->name         = 'Name';
$lang->task->type         = 'Type';
$lang->task->pri          = 'Pri';
$lang->task->owner        = 'Owner';
$lang->task->mailto       = 'Mailto';
$lang->task->estimate     = 'Estimate';
$lang->task->estimateAB   = 'Estimate';
$lang->task->left         = 'Left';
$lang->task->leftAB       = 'Left';
$lang->task->consumed     = 'Consumed';
$lang->task->consumedAB   = 'Consumed';
$lang->task->deadline     = 'Deadline';
$lang->task->deadlineAB   = 'Deadline';
$lang->task->status       = 'Status';
$lang->task->desc         = 'Desc';
$lang->task->statusCustom = 'Status Order';

$lang->task->statusList['wait']    = 'Waiting';
$lang->task->statusList['doing']   = 'Doing';
$lang->task->statusList['done']    = 'Done';
$lang->task->statusList['cancel']  = 'Canceled';

$lang->task->typeList[''] = '';
$lang->task->typeList['design'] = 'Design';
$lang->task->typeList['devel']  = 'Devel';
$lang->task->typeList['test']   = 'Test';
$lang->task->typeList['study']  = 'Study';
$lang->task->typeList['discuss']= 'Discuss';
$lang->task->typeList['ui']     = 'UI';
$lang->task->typeList['affair'] = 'Affair';
$lang->task->typeList['misc']   = 'Misc';

$lang->task->priList[0] = '';
$lang->task->priList[3]  = '3';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[4]  = '4';

$lang->task->afterChoices['continueAdding'] = 'Continue add task for this story';
$lang->task->afterChoices['toTastList']     = 'To task list';
$lang->task->afterChoices['toStoryList']    = 'To story list';

$lang->task->buttonEdit       = 'Edit';
$lang->task->buttonClose      = 'Close';
$lang->task->buttonCancel     = 'Cancel';
$lang->task->buttonActivate   = 'Activate';
$lang->task->buttonLogEfforts = 'Efforts';
$lang->task->buttonDelete     = 'Delete';
$lang->task->buttonBackToList = 'Back';
$lang->task->buttonStart      = 'Start';
$lang->task->buttonDone       = 'Done';

$lang->task->legendBasic  = 'Basic info';
$lang->task->legendEffort = 'Effort';
$lang->task->legendDesc   = 'Desc';
$lang->task->legendAction = 'Action';

$lang->task->ajaxGetUserTasks    = "API:My tasks";
$lang->task->ajaxGetProjectTasks = "API:Project tasks";
$lang->task->confirmDelete       = "Are you sure to delete this task?";
$lang->task->copyStoryTitle      = "Same as story";
$lang->task->afterSubmit         = "After created";
$lang->task->successSaved        = "Success saved";
$lang->task->delayWarning        = " <strong class='delayed f-14px'> Postponed %s days </strong>";
