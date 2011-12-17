<?php
/**
 * The task module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->task->index              = "Index";
$lang->task->create             = "Create";
$lang->task->batchCreate        = "Batch create";
$lang->task->import             = "Import undone";
$lang->task->edit               = "Update";
$lang->task->delete             = "Delete";
$lang->task->view               = "Info";
$lang->task->logEfforts         = "Efforts";
$lang->task->start              = "Start";
$lang->task->finish             = "Finish";
$lang->task->close              = "Close";
$lang->task->batchClose         = "Batch close";
$lang->task->cancel             = "Cancel";
$lang->task->activate           = "Activate";
$lang->task->export             = "Export";
$lang->task->reportChart        = "Report chart";
$lang->task->fromBug            = 'From Bug';
$lang->task->confirmStoryChange = "Confirm story change";

$lang->task->common         = 'Task';
$lang->task->id             = 'ID';
$lang->task->project        = 'Project';
$lang->task->story          = 'Story';
$lang->task->storyVersion   = 'Version';
$lang->task->name           = 'Name';
$lang->task->type           = 'Type';
$lang->task->pri            = 'Pri';
$lang->task->mailto         = 'Mailto';
$lang->task->estimate       = 'Estimate';
$lang->task->estimateAB     = 'Est';
$lang->task->left           = 'Left';
$lang->task->leftAB         = 'Left';
$lang->task->consumed       = 'Consumed';
$lang->task->consumedAB     = 'Use';
$lang->task->deadline       = 'Deadline';
$lang->task->deadlineAB     = 'Deadline';
$lang->task->status         = 'Status';
$lang->task->statusCustom   = 'Status Order';
$lang->task->desc           = 'Desc';
$lang->task->assign         = 'Assign';
$lang->task->assignedTo     = 'Assigned To';
$lang->task->assignedToAB   = 'Assign';
$lang->task->assignedDate   = 'Assigned Date';
$lang->task->openedBy       = 'Opened By';
$lang->task->openedByAB     = 'Open';
$lang->task->openedDate     = 'Opened Date';
$lang->task->openedDateAB   = 'Open';
$lang->task->finishedBy     = 'Finished By';
$lang->task->finishedByAB   = 'Finishe';
$lang->task->finishedDate   = 'Finished Date';
$lang->task->finishedDateAB = 'Date';
$lang->task->canceledBy     = 'Canceled By';
$lang->task->canceledDate   = 'Canceled Date';
$lang->task->closedBy       = 'Closed By';
$lang->task->closedDate     = 'Closed Date';
$lang->task->closedReason   = 'Closed Reason';
$lang->task->lastEditedBy   = 'Last Edited By';
$lang->task->lastEditedDate = 'Last Edited Date';
$lang->task->lastEdited     = 'Last Edited';

$lang->task->same  = 'The same as above';
$lang->task->notes = '(Notes: the name, type, pri and estimate must be written, otherwise it is no use)';

$lang->task->statusList['']        = '';
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
$lang->task->remindBug           = "This task from Bug, update the Bug:%s or not?";

/* Report. */
$lang->task->report->common        = 'Report';
$lang->task->report->select        = 'Select';
$lang->task->report->create        = 'Create';
$lang->task->report->selectAll     = 'All';
$lang->task->report->selectReverse = 'Reverse';

$lang->task->report->charts['tasksPerProject']      = 'Project tasks';
$lang->task->report->charts['tasksPerAssignedTo']   = 'Assigned To';
$lang->task->report->charts['tasksPerType']         = 'Type';
$lang->task->report->charts['tasksPerPri']          = 'Priority';
$lang->task->report->charts['tasksPerStatus']       = 'Status';
$lang->task->report->charts['tasksPerDeadline']     = 'Deadline';
$lang->task->report->charts['tasksPerEstimate']     = 'Estimate time';
$lang->task->report->charts['tasksPerLeft']         = 'Left time';
$lang->task->report->charts['tasksPerConsumed']     = 'Consumed time';
$lang->task->report->charts['tasksPerFinishedBy']   = 'Finished By';
$lang->task->report->charts['tasksPerClosedReason'] = 'Closed reason';
$lang->task->report->charts['finishedTasksPerDay']  = 'Finished tasks per day';

$lang->task->report->options->swf                     = 'pie2d';
$lang->task->report->options->width                   = 'auto';
$lang->task->report->options->height                  = 300;
$lang->task->report->options->graph->baseFontSize     = 12;
$lang->task->report->options->graph->showNames        = 1;
$lang->task->report->options->graph->formatNumber     = 1;
$lang->task->report->options->graph->decimalPrecision = 0;
$lang->task->report->options->graph->animation        = 0;
$lang->task->report->options->graph->rotateNames      = 0;
$lang->task->report->options->graph->yAxisName        = 'COUNT';
$lang->task->report->options->graph->pieRadius        = 100;
$lang->task->report->options->graph->showColumnShadow = 0;

$lang->task->report->tasksPerProject->graph->xAxisName     = 'Project';
$lang->task->report->tasksPerAssignedTo->graph->xAxisName  = 'User';
$lang->task->report->tasksPerType->graph->xAxisName        = 'Type';
$lang->task->report->tasksPerPri->graph->xAxisName         = 'Pri';
$lang->task->report->tasksPerStatus->graph->xAxisName      = 'Status';
$lang->task->report->tasksPerDeadline->graph->xAxisName    = 'Date';
$lang->task->report->tasksPerEstimate->graph->xAxisName    = 'Time';
$lang->task->report->tasksPerLeft->graph->xAxisName        = 'Time';
$lang->task->report->tasksPerConsumed->graph->xAxisName    = 'Time';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName  = 'User';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = 'Closed Reason';

$lang->task->report->finishedTasksPerDay->swf         = 'column2d';
$lang->task->report->finishedTasksPerDay->height      = 400;
$lang->task->report->finishedTasksPerDay->graph->xAxisName   = 'Date';
$lang->task->report->finishedTasksPerDay->graph->rotateNames = '1';
