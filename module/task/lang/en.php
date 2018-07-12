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
$lang->task->index               = "Index";
$lang->task->create              = "Create Task";
$lang->task->batchCreate         = "Batch Create";
$lang->task->batchCreateChildren = "Batch Create Children";
$lang->task->batchEdit           = "Batch Edit";
$lang->task->batchChangeModule   = "Batch Change Module";
$lang->task->batchClose          = "Batch Close";
$lang->task->batchCancel         = "Batch Cancel";
$lang->task->edit                = "Edit";
$lang->task->delete              = "Delete";
$lang->task->deleted             = "Deleted";
$lang->task->delayed             = 'Delayed';
$lang->task->view                = "Overview";
$lang->task->logEfforts          = "Hour";
$lang->task->record              = "Est.";
$lang->task->start               = "Start";
$lang->task->restart             = "Continue";
$lang->task->finish              = "Finish";
$lang->task->pause               = "Pause";
$lang->task->close               = "Close";
$lang->task->cancel              = "Cancel";
$lang->task->activate            = "Activate";
$lang->task->export              = "Export Data";
$lang->task->reportChart         = "Report Chart";
$lang->task->fromBug             = 'From Bug';
$lang->task->case                = 'Related Case';
$lang->task->confirmStoryChange  = "Confirm Story Change";
$lang->task->progress            = 'Progress';
$lang->task->progressTips        = 'Cost/(Cost+Left)';
$lang->task->copy                = 'Duplicate Task';

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
$lang->task->mailto            = 'Mail to';
$lang->task->estimate          = 'Est.(h)';
$lang->task->estimateAB        = 'Est.(h)';
$lang->task->left              = 'Left';
$lang->task->leftAB            = 'Left';
$lang->task->consumed          = 'Cost';
$lang->task->myConsumed        = 'My Cost';
$lang->task->consumedAB        = 'Cost';
$lang->task->hour              = 'Hour';
$lang->task->consumedThisTime  = 'Hour';
$lang->task->leftThisTime      = 'Left';
$lang->task->datePlan          = 'Plan';
$lang->task->estStarted        = 'Estimate Start';
$lang->task->realStarted       = 'Actual Start';
$lang->task->date              = 'Date';
$lang->task->deadline          = 'Deadline';
$lang->task->deadlineAB        = 'Deadline';
$lang->task->status            = 'Status';
$lang->task->desc              = 'Description';
$lang->task->assign            = 'Assign';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->batchAssignTo     = 'Batch Assign';
$lang->task->assignedTo        = 'Assignee';
$lang->task->assignedToAB      = 'Assignee';
$lang->task->assignedDate      = 'Assign on';
$lang->task->openedBy          = 'Created by';
$lang->task->openedDate        = 'Created on';
$lang->task->openedDateAB      = 'Add';
$lang->task->finishedBy        = 'Finished';
$lang->task->finishedByAB      = 'Finished';
$lang->task->finishedDate      = 'Finished on';
$lang->task->finishedDateAB    = 'Finished on';
$lang->task->canceledBy        = 'Cancelled by';
$lang->task->canceledDate      = 'Cancelled on';
$lang->task->closedBy          = 'Closed by';
$lang->task->closedDate        = 'Close Date';
$lang->task->closedReason      = 'Cancel Reason';
$lang->task->lastEditedBy      = 'Edited by';
$lang->task->lastEditedDate    = 'Edited on';
$lang->task->lastEdited        = 'Last Edited';
$lang->task->recordEstimate    = 'Make Progress';
$lang->task->editEstimate      = 'Edit Est.';
$lang->task->deleteEstimate    = 'Delete Est.';
$lang->task->colorTag          = 'Color Tag';
$lang->task->files             = 'Files';
$lang->task->hasConsumed       = 'Cost';
$lang->task->multiple          = 'Multiple Tasks';
$lang->task->multipleAB        = ' Multiple';
$lang->task->team              = 'Team';
$lang->task->transfer          = 'Transfer';
$lang->task->transferTo        = 'Transfer To';
$lang->task->children          = 'Child Task';
$lang->task->childrenAB        = 'Child';
$lang->task->parent            = 'Parent Task';
$lang->task->parentAB          = 'Parent Task';
$lang->task->lblPri            = 'P';
$lang->task->lblHour           = '(h)';

$lang->task->ditto             = 'Ditto';
$lang->task->dittoNotice       = "This Task does not belong to the Project as the previous one does!";
$lang->task->selectAllUser     = 'All';
$lang->task->noStory           = 'No Story';
$lang->task->noAssigned        = 'Unassigned';
$lang->task->noFinished        = 'Unfinished';
$lang->task->noClosed          = 'Unclosed';
$lang->task->yesterdayFinished = 'Finished Yesterday';
$lang->task->allTasks          = 'All Tasks';

$lang->task->statusList['']        = '';
$lang->task->statusList['wait']    = 'Wait';
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
$lang->task->typeList['misc']    = 'Misc';

$lang->task->priList[0]  = '';
$lang->task->priList[3]  = '3';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[4]  = '4';

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = 'Done';
$lang->task->reasonList['cancel'] = 'Cancelled';

$lang->task->afterChoices['continueAdding'] = ' Continue adding Tasks';
$lang->task->afterChoices['toTaskList']     = 'Back to Task List';
$lang->task->afterChoices['toStoryList']    = 'Back to Story List';

$lang->task->legendBasic  = 'Basic Info';
$lang->task->legendEffort = 'Effort';
$lang->task->legendLife   = 'Task Life';
$lang->task->legendDesc   = 'Task Description';

$lang->task->confirmDelete         = "Do you want to delete this Task?";
$lang->task->confirmDeleteEstimate = "Do you want to delete it?";
$lang->task->copyStoryTitle        = "Copy Story";
$lang->task->afterSubmit           = "After Created,";
$lang->task->successSaved          = "Created!";
$lang->task->delayWarning          = " <strong class='text-danger'> Delay %s days </strong>";
$lang->task->remindBug             = "This Task is converted from a Bug. Do you want to update the Bug:%s?";
$lang->task->confirmChangeProject  = "If you change {$lang->projectCommon}, the related Module, Story and Assignor will be changed. Do you want to do it?";
$lang->task->confirmFinish         = '"Left Hour" is 0. Do you want to change the Status to "Done"?';
$lang->task->confirmRecord         = '"Left Hour" is 0. Do you want to set Task as "Done"?';
$lang->task->confirmTransfer       = '"Left Hour" is 0，Do you want to transfer task?';
$lang->task->noticeLinkStory       = "No story has been linked. You can %s for this project, then %s.";
$lang->task->noticeSaveRecord      = 'Your Hour is not saved. Please save it first.';
$lang->task->commentActions        = '%s. %s, commented by <strong>%s</strong>.';
$lang->task->deniedNotice          = 'Only the %s can %s the task.';
$lang->task->noTask                = 'No tasks. ';
$lang->task->createDenied          = 'Create task is denied in this project';

$lang->task->error = new stdclass();
$lang->task->error->consumedNumber   = '"Consumed" must be numbers.';
$lang->task->error->estimateNumber   = '"Hour" must be numbers.';
$lang->task->error->consumedSmall    = '"Consumed" must larger than before.';
$lang->task->error->consumedThisTime = 'Please enter "Hour"';
$lang->task->error->left             = 'Please enter "Left Hour"';
$lang->task->error->work             = '"Remark" must be less than %d characteres.';
$lang->task->error->skipClose        = 'Task: %s is not “Done” or “Cancelled”. Do you want to close it?';
$lang->task->error->consumed         = 'Task: %s Hour must be more than 0. Ignore changes to this Task.';
$lang->task->error->assignedTo       = 'Multiple task in the current state cannot be assigned to member who outside the task team.';

/* Report. */
$lang->task->report = new stdclass();
$lang->task->report->common = 'Report';
$lang->task->report->select = 'Group Tasks by';
$lang->task->report->create = 'Create';
$lang->task->report->value  = 'Task Count';

$lang->task->report->charts['tasksPerProject']      = 'by ' . $lang->projectCommon;
$lang->task->report->charts['tasksPerModule']       = 'by Module';
$lang->task->report->charts['tasksPerAssignedTo']   = 'by Assignee';
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
$lang->task->report->tasksPerAssignedTo->item   = 'Account';
$lang->task->report->tasksPerType->item         = 'Type';
$lang->task->report->tasksPerPri->item          = 'Priority';
$lang->task->report->tasksPerStatus->item       = 'Status';
$lang->task->report->tasksPerDeadline->item     = 'Date';
$lang->task->report->tasksPerEstimate->item     = 'Hour';
$lang->task->report->tasksPerLeft->item         = 'Left';
$lang->task->report->tasksPerConsumed->item     = 'Cost';
$lang->task->report->tasksPerFinishedBy->item   = 'User';
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
$lang->task->report->tasksPerEstimate->graph->xAxisName     = 'Hour';
$lang->task->report->tasksPerLeft->graph->xAxisName         = 'Left';
$lang->task->report->tasksPerConsumed->graph->xAxisName     = 'Cost';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName   = 'User';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = 'Close Reason';

$lang->task->report->finishedTasksPerDay->type               = 'bar';
$lang->task->report->finishedTasksPerDay->graph->xAxisName   = 'Date';
