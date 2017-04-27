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
$lang->task->index              = "Index";
$lang->task->create             = "Create";
$lang->task->batchCreate        = "Batch Create";
$lang->task->batchEdit          = "Batch Edit";
$lang->task->batchChangeModule  = "Batch Change Module";
$lang->task->batchClose         = "Batch Close";
$lang->task->batchCancel        = "Batch Cancel";
$lang->task->edit               = "Update";
$lang->task->delete             = "Delete";
$lang->task->deleted            = "Deleted";
$lang->task->delayed            = 'Delayed';
$lang->task->view               = "Info";
$lang->task->logEfforts         = "Man-Hour Log";
$lang->task->record             = "Est.";
$lang->task->start              = "Start";
$lang->task->restart            = "Continue";
$lang->task->finish             = "Finish";
$lang->task->pause              = "Pause";
$lang->task->close              = "Close";
$lang->task->cancel             = "Cancel";
$lang->task->activate           = "Activate";
$lang->task->export             = "Export Data";
$lang->task->reportChart        = "Report Chart";
$lang->task->fromBug            = 'From Bug';
$lang->task->case               = 'Related Case';
$lang->task->confirmStoryChange = "Confirm Story Change";
$lang->task->progess            = 'Progess';
$lang->task->progessTips        = 'Consumed/(Consumed+Remained)';
$lang->task->copy               = 'Duplicate a Task';

$lang->task->common            = 'Task';
$lang->task->id                = 'ID';
$lang->task->project           = $lang->projectCommon;
$lang->task->module            = 'Module';
$lang->task->moduleAB          = 'Module';
$lang->task->story             = 'Story';
$lang->task->storySpec         = 'Story Description';
$lang->task->storyVerify       = 'Acceptance';
$lang->task->name              = 'Name';
$lang->task->type              = 'Type';
$lang->task->pri               = 'Priority';
$lang->task->mailto            = 'Mailto';
$lang->task->estimate          = 'Estimated';
$lang->task->estimateAB        = 'Estimated';
$lang->task->left              = 'Remained';
$lang->task->leftAB            = 'Remained';
$lang->task->consumed          = 'Consumed';
$lang->task->consumedAB        = 'Consume';
$lang->task->hour              = 'Hour';
$lang->task->consumedThisTime  = 'Man-Hour';
$lang->task->leftThisTime      = 'Remained';
$lang->task->datePlan          = 'Plan';
$lang->task->estStarted        = 'Start Date';
$lang->task->realStarted       = 'Actual Start';
$lang->task->date              = 'Date';
$lang->task->deadline          = 'Finish Date';
$lang->task->deadlineAB        = 'Deadline';
$lang->task->status            = 'Status';
$lang->task->desc              = 'Description';
$lang->task->assign            = 'Assign';
$lang->task->assignTo          = $lang->task->assign;
$lang->task->batchAssignTo     = 'Batch Assign';
$lang->task->assignedTo        = 'Assigned To';
$lang->task->assignedToAB      = 'Assigned To';
$lang->task->assignedDate      = 'Assigned Date';
$lang->task->openedBy          = 'Created By';
$lang->task->openedDate        = 'Created Date';
$lang->task->openedDateAB      = 'Add';
$lang->task->finishedBy        = 'Finished By';
$lang->task->finishedByAB      = 'Finish';
$lang->task->finishedDate      = 'Finished on';
$lang->task->finishedDateAB    = 'Date';
$lang->task->canceledBy        = 'Cancelled By';
$lang->task->canceledDate      = 'Cancelled on';
$lang->task->closedBy          = 'Closed By';
$lang->task->closedDate        = 'Close Date';
$lang->task->closedReason      = 'Cancelled Reason';
$lang->task->lastEditedBy      = 'Last Edited By';
$lang->task->lastEditedDate    = 'Last Edited on';
$lang->task->lastEdited        = 'Last Edited';
$lang->task->recordEstimate    = 'Man-Hour';
$lang->task->editEstimate      = 'Edit Man-Hour';
$lang->task->deleteEstimate    = 'Delete Man-Hour';
$lang->task->colorTag          = 'Color Tag';
$lang->task->files             = 'Files';
$lang->task->hasConsumed       = 'Consumed';

$lang->task->ditto         = 'Ditto';
$lang->task->dittoNotice   = "This Task does not belong to the Project as the previous one does!";
$lang->task->selectAllUser = 'All';
$lang->task->noStory       = 'No Story';
$lang->task->noAssigned    = 'Unassigned';
$lang->task->noFinished    = 'Unfinished';
$lang->task->noClosed      = 'Unclosed';

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
$lang->task->legendEffort = 'Man-Hour';
$lang->task->legendLife   = 'Task Life';
$lang->task->legendDesc   = 'Task Description';

$lang->task->confirmDelete         = "Do you want to delete this Task?";
$lang->task->confirmDeleteEstimate = "Do you want to delete it?";
$lang->task->copyStoryTitle        = "Same Story";
$lang->task->afterSubmit           = "After Created,";
$lang->task->successSaved          = "Created!";
$lang->task->delayWarning          = " <strong class='delayed f-14px'> Delay %s days </strong>";
$lang->task->remindBug             = "This Task is converted from a Bug. Do you want to update te Bug:%s?";
$lang->task->confirmChangeProject  = "If you change {$lang->projectCommon}, the related Module, Story and Assignor will be changed. Do you want to do it?";
$lang->task->confirmFinish         = '"Estimated remianed hour" is 0. Do you want to change the Status to "Done"?';
$lang->task->confirmRecord         = '"Remain hour" is 0. Do you want to set Task as "Done"?';
$lang->task->noticeLinkStory       = "No story has been related. You can %s for this project, then %s.";
$lang->task->noticeSaveRecord      = 'Your man-hour is not saved. Please save it first.';
$lang->task->commentActions        = '%s. %s, commented by <strong>%s</strong>.';

$lang->task->error = new stdclass();
$lang->task->error->consumedNumber   = '"Consumed" must be numbers.';
$lang->task->error->estimateNumber   = '"Estimated" must be numbers.';
$lang->task->error->consumedSmall    = '"Consumed" must larger than before.';
$lang->task->error->consumedThisTime = 'Please enter "Man-Hour"';
$lang->task->error->left             = 'Please enter "Remained"';
$lang->task->error->work             = '"Remark" must be less than 255 characteres.';
$lang->task->error->skipClose        = 'Task: %s is not“Done”or “Cancelled”. Confirm to close them?';
$lang->task->error->consumed         = 'Task: %s man-hour must be more than 0. Ignore changes to this Task.';

/* 统计报表。*/
$lang->task->report = new stdclass();
$lang->task->report->common = 'Report';
$lang->task->report->select = 'Select the type of Task Report';
$lang->task->report->create = 'Create';
$lang->task->report->value  = 'Task Count';

$lang->task->report->charts['tasksPerProject']      = 'per ' . $lang->projectCommon;
$lang->task->report->charts['tasksPerModule']       = 'per Module';
$lang->task->report->charts['tasksPerAssignedTo']   = 'per AssignedTo';
$lang->task->report->charts['tasksPerType']         = 'per Category';
$lang->task->report->charts['tasksPerPri']          = 'per Priority';
$lang->task->report->charts['tasksPerStatus']       = 'per Status';
$lang->task->report->charts['tasksPerDeadline']     = 'per Deadline';
$lang->task->report->charts['tasksPerEstimate']     = 'per Estimated Man-Hour';
$lang->task->report->charts['tasksPerLeft']         = 'per Remained Man-Hour';
$lang->task->report->charts['tasksPerConsumed']     = 'per Consumed Man-Hour';
$lang->task->report->charts['tasksPerFinishedBy']   = 'per FinishedBy';
$lang->task->report->charts['tasksPerClosedReason'] = 'per Close Reason';
$lang->task->report->charts['finishedTasksPerDay']  = 'per Finished/Day';

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
$lang->task->report->tasksPerEstimate->item     = 'Est.';
$lang->task->report->tasksPerLeft->item         = 'Remain';
$lang->task->report->tasksPerConsumed->item     = 'Consume';
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
$lang->task->report->tasksPerEstimate->graph->xAxisName     = 'Time';
$lang->task->report->tasksPerLeft->graph->xAxisName         = 'Time';
$lang->task->report->tasksPerConsumed->graph->xAxisName     = 'Time';
$lang->task->report->tasksPerFinishedBy->graph->xAxisName   = 'User';
$lang->task->report->tasksPerClosedReason->graph->xAxisName = 'Close Reason';

$lang->task->report->finishedTasksPerDay->type               = 'bar';
$lang->task->report->finishedTasksPerDay->graph->xAxisName   = 'Date';
