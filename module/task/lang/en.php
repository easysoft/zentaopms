<?php
/**
 * The task module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: en.php 5040 2013-07-06 06:22:18Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->task->index              = "Index";
$lang->task->create             = "Create";
$lang->task->batchCreate        = "Batch create";
$lang->task->batchEdit          = "Batch edit";
$lang->task->import             = "Import undone";
$lang->task->edit               = "Update";
$lang->task->delete             = "Delete";
$lang->task->deleted            = "Deleted";
$lang->task->view               = "Info";
$lang->task->logEfforts         = "Log working hours";
$lang->task->record             = "Estimate";
$lang->task->start              = "Start";
$lang->task->restart            = "Continue";
$lang->task->finish             = "Finish";
$lang->task->pause              = "Pause";
$lang->task->close              = "Close";
$lang->task->batchClose         = "Batch close";
$lang->task->cancel             = "Cancel";
$lang->task->activate           = "Activate";
$lang->task->export             = "Export data";
$lang->task->reportChart        = "Report chart";
$lang->task->fromBug            = 'From Bug';
$lang->task->confirmStoryChange = "Confirm story change";

$lang->task->common            = 'Task';
$lang->task->id                = 'ID';
$lang->task->project           = 'Project';
$lang->task->module            = 'Module';
$lang->task->story             = 'Story';
$lang->task->storySpec         = 'Story desc';
$lang->task->storyVersion      = 'Version';
$lang->task->name              = 'Name';
$lang->task->type              = 'Type';
$lang->task->pri               = 'Pri';
$lang->task->mailto            = 'Mailto';
$lang->task->estimate          = 'Estimate';
$lang->task->estimateAB        = 'Est';
$lang->task->left              = 'Left';
$lang->task->leftAB            = 'Left';
$lang->task->consumed          = 'Consumed';
$lang->task->consumedAB        = 'Use';
$lang->task->hour              = 'Hour';
$lang->task->consumedThisTime  = 'Hours';
$lang->task->leftThisTime      = 'Left';
$lang->task->estStarted        = 'Estimate start';
$lang->task->realStarted       = 'Real start';
$lang->task->date              = 'Date';
$lang->task->deadline          = 'Deadline';
$lang->task->deadlineAB        = 'Deadline';
$lang->task->status            = 'Status';
$lang->task->desc              = 'Desc';
$lang->task->assign            = 'Assign';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->batchAssignTo     = 'Batch assign';
$lang->task->assignedTo        = 'Assigned To';
$lang->task->assignedToAB      = 'Assign';
$lang->task->assignedDate      = 'Assigned Date';
$lang->task->openedBy          = 'Opened By';
$lang->task->openedByAB        = 'Open';
$lang->task->openedDate        = 'Opened Date';
$lang->task->openedDateAB      = 'Open';
$lang->task->finishedBy        = 'Finished By';
$lang->task->finishedByAB      = 'Finish';
$lang->task->finishedDate      = 'Finished Date';
$lang->task->finishedDateAB    = 'Date';
$lang->task->canceledBy        = 'Canceled By';
$lang->task->canceledDate      = 'Canceled Date';
$lang->task->closedBy          = 'Closed By';
$lang->task->closedDate        = 'Closed Date';
$lang->task->closedReason      = 'Closed Reason';
$lang->task->lastEditedBy      = 'Last Edited By';
$lang->task->lastEditedDate    = 'Last Edited Date';
$lang->task->lastEdited        = 'Last Edited';
$lang->task->recordEstimate    = 'Hours';
$lang->task->editEstimate      = 'Edit Hour';
$lang->task->deleteEstimate    = 'Delete Hour';

$lang->task->ditto = 'Ditto';

$lang->task->statusList['']        = '';
$lang->task->statusList['wait']    = 'Pending';
$lang->task->statusList['doing']   = 'Doing';
$lang->task->statusList['done']    = 'Done';
$lang->task->statusList['pause']   = 'Paused';
$lang->task->statusList['cancel']  = 'Canceled';
$lang->task->statusList['closed']  = 'Closed';

$lang->task->typeList['']        = '';
$lang->task->typeList['design']  = 'Design';
$lang->task->typeList['devel']   = 'Devel';
$lang->task->typeList['test']    = 'Test';
$lang->task->typeList['study']   = 'Study';
$lang->task->typeList['discuss'] = 'Discuss';
$lang->task->typeList['ui']      = 'UI';
$lang->task->typeList['affair']  = 'Affair';
$lang->task->typeList['misc']    = 'Misc';

$lang->task->priList[0]  = '';
$lang->task->priList[3]  = '3';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[4]  = '4';

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = 'Done';
$lang->task->reasonList['cancel'] = 'Canceled';

$lang->task->afterChoices['continueAdding'] = 'Continue to add task for this story. ';
$lang->task->afterChoices['toTaskList']     = 'To task list. ';
$lang->task->afterChoices['toStoryList']    = 'To story list. ';

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

$lang->task->ajaxGetUserTasks      = "API:My tasks";
$lang->task->ajaxGetProjectTasks   = "API:Project tasks";
$lang->task->confirmDelete         = "Are you sure you want to delete this task?";
$lang->task->confirmDeleteEstimate = "Are you sure you want to delete this hour?";
$lang->task->copyStoryTitle        = "Same as story";
$lang->task->afterSubmit           = "After created";
$lang->task->successSaved          = "Successfully saved";
$lang->task->delayWarning          = " <strong class='delayed f-14px'> Postponed %s days </strong>";
$lang->task->remindBug             = "This task from Bug, update the Bug:%s or not?";
$lang->task->confirmChangeProject  = 'Change project will change module, story and assignedTo also, are you sure?';
$lang->task->confirmFinish         = '"Left" is zero, this task will be done. Are you sure?';
$lang->task->confirmRecord         = '"Left" is zero, this task will be done. Are you sure?';

$lang->task->error = new stdclass();
$lang->task->error->consumedNumber   = '"Consumed" must be number';
$lang->task->error->estimateNumber   = '"Estimate" must be number';
$lang->task->error->consumedSmall    = '"Consumed" must be more than consumed before';
$lang->task->error->consumedThisTime = 'Please input "hours"';
$lang->task->error->left             = 'Please input "left"';
$lang->task->error->work             = '"Comment" must be less than 255 characters';
$lang->task->error->skipClose        = 'The status of Tasks : %s are not finished or canceled, can not close.';

/* Report. */
$lang->task->report = new stdclass();
$lang->task->report->common = 'Report';
$lang->task->report->select = 'Select';
$lang->task->report->create = 'Create';
$lang->task->report->value  = 'Tasks';

$lang->task->report->charts['tasksPerProject']      = 'Project tasks';
$lang->task->report->charts['tasksPerModule']       = 'Module tasks';
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

$lang->task->report->options = new stdclass();
$lang->task->report->options->graph = new stdclass();
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

$lang->task->report->tasksPerProject      = new stdclass();
$lang->task->report->tasksPerModule       = new stdclass();
$lang->task->report->tasksPerAssignedTo   = new stdclass();
$lang->task->report->tasksPerType         = new stdclass();
$lang->task->report->tasksPerPri          = new stdclass();
$lang->task->report->tasksPerStatus       = new stdclass();
$lang->task->report->tasksPerDeadline     = new stdclass();
$lang->task->report->tasksPerEstimate     = new stdclass();
$lang->task->report->tasksPerLeft         = new stdclass();
$lang->task->report->tasksPerConsumed     = new stdclass();
$lang->task->report->tasksPerFinishedBy   = new stdclass();
$lang->task->report->tasksPerClosedReason = new stdclass();
$lang->task->report->finishedTasksPerDay  = new stdclass();

$lang->task->report->tasksPerProject->item      ='Project';
$lang->task->report->tasksPerModule->item       ='Module';
$lang->task->report->tasksPerAssignedTo->item   ='Account';
$lang->task->report->tasksPerType->item         ='Type';
$lang->task->report->tasksPerPri->item          ='Pri';
$lang->task->report->tasksPerStatus->item       ='Status';
$lang->task->report->tasksPerDeadline->item     ='Date';
$lang->task->report->tasksPerEstimate->item     ='Estimate';
$lang->task->report->tasksPerLeft->item         ='Left';
$lang->task->report->tasksPerConsumed->item     ='Consumed';
$lang->task->report->tasksPerFinishedBy->item   ='Account';
$lang->task->report->tasksPerClosedReason->item ='Reason';
$lang->task->report->finishedTasksPerDay->item  ='Date';

$lang->task->report->tasksPerProject->graph      = new stdclass();
$lang->task->report->tasksPerModule->graph       = new stdclass();
$lang->task->report->tasksPerAssignedTo->graph   = new stdclass();
$lang->task->report->tasksPerType->graph         = new stdclass();
$lang->task->report->tasksPerPri->graph          = new stdclass();
$lang->task->report->tasksPerStatus->graph       = new stdclass();
$lang->task->report->tasksPerDeadline->graph     = new stdclass();
$lang->task->report->tasksPerEstimate->graph     = new stdclass();
$lang->task->report->tasksPerLeft->graph         = new stdclass();
$lang->task->report->tasksPerConsumed->graph     = new stdclass();
$lang->task->report->tasksPerFinishedBy->graph   = new stdclass();
$lang->task->report->tasksPerClosedReason->graph = new stdclass();
$lang->task->report->finishedTasksPerDay->graph  = new stdclass();

$lang->task->report->tasksPerProject->graph->xAxisName      = 'Project';
$lang->task->report->tasksPerModule->graph->xAxisName       = 'Module';
$lang->task->report->tasksPerAssignedTo->graph->xAxisName   = 'User';
$lang->task->report->tasksPerType->graph->xAxisName         = 'Type';
$lang->task->report->tasksPerPri->graph->xAxisName          = 'Pri';
$lang->task->report->tasksPerStatus->graph->xAxisName       = 'Status';
$lang->task->report->tasksPerDeadline->graph->xAxisName     = 'Date';
$lang->task->report->tasksPerEstimate->graph->xAxisName     = 'Time';
$lang->task->report->tasksPerLeft->graph->xAxisName         = 'Time';
$lang->task->report->tasksPerConsumed->graph->xAxisName     = 'Time';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName   = 'User';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = 'Closed Reason';

$lang->task->report->finishedTasksPerDay->swf                = 'column2d';
$lang->task->report->finishedTasksPerDay->height             = 400;
$lang->task->report->finishedTasksPerDay->graph->xAxisName   = 'Date';
$lang->task->report->finishedTasksPerDay->graph->rotateNames = '1';

$lang->task->estimateTip = 'The estimated time for this task';
