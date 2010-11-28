<?php
/**
 * The task module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
$lang->task->finish    = "Finish";
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
$lang->task->statusCustom = 'Status Order';
$lang->task->desc         = 'Desc';
$lang->task->assignedTo   = 'Assigned To';
$lang->task->assignedDate = 'Assigned Date';
$lang->task->openedBy     = 'Opened By';
$lang->task->openedDate   = 'Opened Date';
$lang->task->finishedBy   = 'Finished By';
$lang->task->finishedDate = 'Finished Date';
$lang->task->canceledBy   = 'Canceled By';
$lang->task->canceledDate = 'Canceled Date';
$lang->task->closedBy     = 'Closed By';
$lang->task->closedDate   = 'Closed Date';
$lang->task->closedReason = 'Closed Reason';
$lang->task->lastEdited   = 'Last Edited';

$lang->task->statusList['wait']    = 'Waiting';
$lang->task->statusList['doing']   = 'Doing';
$lang->task->statusList['done']    = 'Done';
$lang->task->statusList['cancel']  = 'Canceled';
$lang->task->statusList['closed']  = 'Closed';

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

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = 'Done';
$lang->task->reasonList['cancel'] = 'Canceled';

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
$lang->task->legendLife   = 'Lifetime';
$lang->task->legendDesc   = 'Desc';
$lang->task->legendAction = 'Action';

$lang->task->ajaxGetUserTasks    = "API:My tasks";
$lang->task->ajaxGetProjectTasks = "API:Project tasks";
$lang->task->confirmDelete       = "Are you sure to delete this task?";
$lang->task->copyStoryTitle      = "Same as story";
$lang->task->afterSubmit         = "After created";
$lang->task->successSaved        = "Success saved";
$lang->task->delayWarning        = " <strong class='delayed f-14px'> Postponed %s days </strong>";
