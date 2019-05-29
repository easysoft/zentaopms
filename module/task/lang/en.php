<?php
/**
 * The task module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: en.php 5040 2013-07-06 06:22:18Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->task->index               = "Home";
$lang->task->create              = "Create Task";
$lang->task->batchCreate         = "Batch Create Task";
$lang->task->batchCreateChildren = "Batch Create Child";
$lang->task->batchEdit           = "Batch Edit";
$lang->task->batchChangeModule   = "Batch Change Module";
$lang->task->batchClose          = "Batch Close";
$lang->task->batchCancel         = "Batch Cancel";
$lang->task->edit                = "Edit";
$lang->task->delete              = "Delete";
$lang->task->deleted             = "Deleted";
$lang->task->delayed             = 'Delayed';
$lang->task->view                = "Overview";
$lang->task->logEfforts          = "Effort";
$lang->task->record              = "Estimates";
$lang->task->start               = "Start";
$lang->task->restart             = "Continue";
$lang->task->finish              = "Finish";
$lang->task->pause               = "Pause";
$lang->task->close               = "Close";
$lang->task->cancel              = "Cancel";
$lang->task->activate            = "Activate";
$lang->task->export              = "Export";
$lang->task->reportChart         = "Report Chart";
$lang->task->fromBug             = 'From Bug';
$lang->task->case                = 'Linked Case';
$lang->task->confirmStoryChange  = "Confirm Change";
$lang->task->storyChange         = "Story Changed";
$lang->task->progress            = '%';
$lang->task->progressTips        = 'Cost/(Cost+Left)';
$lang->task->copy                = 'Copy Task';
$lang->task->waitTask            = 'Waiting Task';

$lang->task->common            = 'Task';
$lang->task->id                = 'ID';
$lang->task->project           = $lang->projectCommon;
$lang->task->module            = 'Module';
$lang->task->moduleAB          = 'Module';
$lang->task->story             = 'Story';
$lang->task->storyAB           = 'Story';
$lang->task->storySpec         = 'Story Description';
$lang->task->storyVerify       = 'Acceptance Criteria';
$lang->task->name              = 'Name';
$lang->task->type              = 'Type';
$lang->task->pri               = 'Priority';
$lang->task->mailto            = 'Mailto';
$lang->task->estimate          = 'Estimates';
$lang->task->estimateAB        = 'Est.(h)';
$lang->task->left              = 'Hours Left';
$lang->task->leftAB            = 'Left';
$lang->task->consumed          = 'Hours Cost';
$lang->task->myConsumed        = 'My Cost';
$lang->task->consumedAB        = 'Cost';
$lang->task->hour              = 'Hours';
$lang->task->consumedThisTime  = 'Cost';
$lang->task->leftThisTime      = 'Left';
$lang->task->datePlan          = 'Timeframe';
$lang->task->estStarted        = 'Start';
$lang->task->realStarted       = 'Started';
$lang->task->date              = 'Date';
$lang->task->deadline          = 'Deadline';
$lang->task->deadlineAB        = 'Deadline';
$lang->task->status            = 'Status';
$lang->task->desc              = 'Description';
$lang->task->assign            = 'Assign';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->batchAssignTo     = 'Batch Assign';
$lang->task->assignedTo        = 'AssignedTo';
$lang->task->assignedToAB      = 'AssignedTo';
$lang->task->assignedDate      = 'Assigned';
$lang->task->openedBy          = 'CreatedBy';
$lang->task->openedDate        = 'Created Date';
$lang->task->openedDateAB      = 'Created';
$lang->task->finishedBy        = 'FinishedBy';
$lang->task->finishedByAB      = 'FinishedBy';
$lang->task->finishedDate      = 'Actual End';
$lang->task->finishedDateAB    = 'Actual End';
$lang->task->finishedList      = 'FinishedBy';
$lang->task->canceledBy        = 'CancelledBy';
$lang->task->canceledDate      = 'Cancelled';
$lang->task->closedBy          = 'ClosedBy';
$lang->task->closedDate        = 'Closed';
$lang->task->closedReason      = 'Cancel Reason';
$lang->task->lastEditedBy      = 'EditedBy';
$lang->task->lastEditedDate    = 'Edited';
$lang->task->lastEdited        = 'Last Edited';
$lang->task->recordEstimate    = '%';
$lang->task->editEstimate      = 'Edit Est.';
$lang->task->deleteEstimate    = 'Delete Est.';
$lang->task->colorTag          = 'Color';
$lang->task->files             = 'Files';
$lang->task->hasConsumed       = 'Cost';
$lang->task->multiple          = 'Multi-user Task';
$lang->task->multipleAB        = ' Multi-user';
$lang->task->team              = 'Team';
$lang->task->transfer          = 'Transfer';
$lang->task->transferTo        = 'Transfer To';
$lang->task->children          = 'Child Task';
$lang->task->childrenAB        = 'Child';
$lang->task->parent            = 'Parent Task';
$lang->task->parentAB          = 'Parent';
$lang->task->lblPri            = 'P';
$lang->task->lblHour           = '(h)';
$lang->task->lblTestStory      = 'Test Story';

$lang->task->ditto             = 'Ditto';
$lang->task->dittoNotice       = "This Task is not linked to the project as the previous one is!";
$lang->task->selectTestStory   = 'Select Test Story';
$lang->task->selectAllUser     = 'All Users';
$lang->task->noStory           = 'No Story';
$lang->task->noAssigned        = 'Unassigned';
$lang->task->noFinished        = 'Unfinished';
$lang->task->noClosed          = 'Unclosed';
$lang->task->yesterdayFinished = 'Task Finished Yesterday';
$lang->task->allTasks          = 'Task';

$lang->task->statusList['']        = '';
$lang->task->statusList['wait']    = 'Waiting';
$lang->task->statusList['doing']   = 'Doing';
$lang->task->statusList['done']    = 'Done';
$lang->task->statusList['pause']   = 'Paused';
$lang->task->statusList['cancel']  = 'Cancelled';
$lang->task->statusList['closed']  = 'Closed';

$lang->task->typeList['']        = '';
$lang->task->typeList['design']  = 'Design';
$lang->task->typeList['devel']   = 'Develop';
$lang->task->typeList['test']    = 'Testing';
$lang->task->typeList['study']   = 'Study';
$lang->task->typeList['discuss'] = 'Discuss';
$lang->task->typeList['ui']      = 'UI';
$lang->task->typeList['affair']  = 'Work';
$lang->task->typeList['misc']    = 'Misc.';

$lang->task->priList[0]  = '';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[3]  = '3';
$lang->task->priList[4]  = '4';

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = 'Done';
$lang->task->reasonList['cancel'] = 'Cancelled';

$lang->task->afterChoices['continueAdding'] = ' Continue Adding Tasks';
$lang->task->afterChoices['toTaskList']     = 'Back to Task List';
$lang->task->afterChoices['toStoryList']    = 'Back to Story List';

$lang->task->legendBasic  = 'Basic Info';
$lang->task->legendEffort = 'Effort';
$lang->task->legendLife   = 'Task Life';
$lang->task->legendDesc   = 'Task Description';

$lang->task->confirmDelete         = "Do you want to delete this Task?";
$lang->task->confirmDeleteEstimate = "Do you want to delete it?";
$lang->task->copyStoryTitle        = "Copy Story";
$lang->task->afterSubmit           = "After it is created, ";
$lang->task->successSaved          = "Created!";
$lang->task->delayWarning          = " <strong class='text-danger'> Delay %s days </strong>";
$lang->task->remindBug             = "This Task is converted from a Bug. Do you want to update the Bug:%s?";
$lang->task->confirmChangeProject  = "If you change {$lang->projectCommon}, the linked Module, Story and AssignedTo will be changed. Do you want to do it?";
$lang->task->confirmFinish         = '"Left Man-hour" is 0. Do you want to change the Status to "Finished"?';
$lang->task->confirmRecord         = '"Left Man-hour" is 0. Do you want to set Task as "Finished"?';
$lang->task->confirmTransfer       = '"Left Man-hour" is 0，Do you want to transfer task?';
$lang->task->noticeLinkStory       = "No story has been linked. You can %s for this project, then %s.";
$lang->task->noticeSaveRecord      = 'Your Man-hour is not saved. Please save it first.';
$lang->task->commentActions        = '%s. %s, commented by <strong>%s</strong>.';
$lang->task->deniedNotice          = 'Only the %s can %s the task.';
$lang->task->noTask                = 'No tasks yet. ';
$lang->task->createDenied          = 'Create Task is denied in this project';

$lang->task->error = new stdclass();
$lang->task->error->consumedNumber   = '"Cost" must be numbers.';
$lang->task->error->estimateNumber   = '"Man-hour" must be numbers.';
$lang->task->error->consumedSmall    = '"Cost" must be >  the previous number.';
$lang->task->error->consumedThisTime = 'Please enter "Man-hour"';
$lang->task->error->left             = 'Please enter "Left Hour"';
$lang->task->error->work             = '"Comment" must be <  %d characters.';
$lang->task->error->skipClose        = 'Task: %s is not Finished” or “Cancelled”. Do you want to close it?';
$lang->task->error->consumed         = 'Task: %s man-hour must be < 0. Ignore changes to this Task.';
$lang->task->error->assignedTo       = 'Multi-user task in the current status cannot be assigned to a member who is not in the task team.';

/* Report. */
$lang->task->report = new stdclass();
$lang->task->report->common = 'Report';
$lang->task->report->select = 'Select Report Type';
$lang->task->report->create = 'Create Report';
$lang->task->report->value  = 'No. of Tasks';

$lang->task->report->charts['tasksPerProject']      = 'by ' . $lang->projectCommon;
$lang->task->report->charts['tasksPerModule']       = 'by Module';
$lang->task->report->charts['tasksPerAssignedTo']   = 'by AssignedTo';
$lang->task->report->charts['tasksPerType']         = 'by Category';
$lang->task->report->charts['tasksPerPri']          = 'by Priority';
$lang->task->report->charts['tasksPerStatus']       = 'by Status';
$lang->task->report->charts['tasksPerDeadline']     = 'by Deadline';
$lang->task->report->charts['tasksPerEstimate']     = 'by Hour';
$lang->task->report->charts['tasksPerLeft']         = 'by Left Hour';
$lang->task->report->charts['tasksPerConsumed']     = 'by Cost Hour';
$lang->task->report->charts['tasksPerFinishedBy']   = 'by FinishedBy';
$lang->task->report->charts['tasksPerClosedReason'] = 'by Close Reason';
$lang->task->report->charts['finishedTasksPerDay']  = 'by Finished/Day';

$lang->task->report->options = new stdclass();
$lang->task->report->options->graph = new stdclass();
$lang->task->report->options->type   = 'pie';
$lang->task->report->options->width  = 500;
$lang->task->report->options->height = 140;

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

$lang->task->report->tasksPerProject->item      = $lang->projectCommon;
$lang->task->report->tasksPerModule->item       = 'Module';
$lang->task->report->tasksPerAssignedTo->item   = 'AssignedTo';
$lang->task->report->tasksPerType->item         = 'Type';
$lang->task->report->tasksPerPri->item          = 'Priority';
$lang->task->report->tasksPerStatus->item       = 'Status';
$lang->task->report->tasksPerDeadline->item     = 'Date';
$lang->task->report->tasksPerEstimate->item     = 'Estimates';
$lang->task->report->tasksPerLeft->item         = 'Man-hours Left';
$lang->task->report->tasksPerConsumed->item     = 'Man-hours Cost';
$lang->task->report->tasksPerFinishedBy->item   = 'FinishedBy';
$lang->task->report->tasksPerClosedReason->item = 'Reason';
$lang->task->report->finishedTasksPerDay->item  = 'Date';

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

$lang->task->report->tasksPerProject->graph->xAxisName      = $lang->projectCommon;
$lang->task->report->tasksPerModule->graph->xAxisName       = 'Module';
$lang->task->report->tasksPerAssignedTo->graph->xAxisName   = 'User';
$lang->task->report->tasksPerType->graph->xAxisName         = 'Type';
$lang->task->report->tasksPerPri->graph->xAxisName          = 'Priority';
$lang->task->report->tasksPerStatus->graph->xAxisName       = 'Status';
$lang->task->report->tasksPerDeadline->graph->xAxisName     = 'Date';
$lang->task->report->tasksPerEstimate->graph->xAxisName     = 'Estimates';
$lang->task->report->tasksPerLeft->graph->xAxisName         = 'Man-Hours Left';
$lang->task->report->tasksPerConsumed->graph->xAxisName     = 'Man-hours Cost';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName   = 'User';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = 'Close Reason';

$lang->task->report->finishedTasksPerDay->type             = 'bar';
$lang->task->report->finishedTasksPerDay->graph->xAxisName = 'Date';
