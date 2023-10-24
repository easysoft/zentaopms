<?php
/**
 * The task module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@cnezsoft.com>
 * @package     task
 * @version     $Id: en.php 5040 2022-02-28 09:36:18Z $
 * @link        https://www.zentao.net
 */
$lang->task->index               = "Home";
$lang->task->create              = "Create Task";
$lang->task->batchCreateChildren = "Batch Create Child Tasks";
$lang->task->edit                = "Edit Task";
$lang->task->deleteAction        = "Delete Task";
$lang->task->view                = "Task Detail";
$lang->task->startAction         = "Start Task";
$lang->task->restartAction       = "Continue Task";
$lang->task->finishAction        = "Finish Task";
$lang->task->pauseAction         = "Pause Task";
$lang->task->closeAction         = "Close Task";
$lang->task->cancelAction        = "Cancel Task";
$lang->task->activateAction      = "Activate Task";
$lang->task->exportAction        = "Export Task";
$lang->task->copy                = 'Copy Task';
$lang->task->waitTask            = 'Waiting Task';
$lang->task->region              = 'Region';
$lang->task->lane                = 'Lane';
$lang->task->execution           = 'Execution';

$lang->task->module       = 'Module';
$lang->task->allModule    = 'All Module';
$lang->task->common       = 'Task';
$lang->task->name         = 'Name';
$lang->task->type         = 'Type';
$lang->task->status       = 'Status';
$lang->task->desc         = 'Description';
$lang->task->assignAction = 'Assign Task';
$lang->task->multiple     = 'Multiple Users';
$lang->task->children     = 'Child Task';
$lang->task->parent       = 'Parent Task';

/* Fields of zt_taskestimate. */
$lang->task->task = 'Task';

$lang->task->dittoNotice       = "This Task is not linked to %s like the last one!";
$lang->task->yesterdayFinished = 'Task Finished Yesterday';
$lang->task->allTasks          = 'Task';

$lang->task->afterChoices['continueAdding'] = ' Continue Adding Tasks';
$lang->task->afterChoices['toTaskList']     = 'Go to Task List';

$lang->task->legendLife   = 'Task Life';
$lang->task->legendDesc   = 'Task Description';
$lang->task->legendDetail = 'Task Detail';

$lang->task->confirmDelete         = "Do you want to delete this task?";
$lang->task->confirmDeleteEstimate = "Do you want to delete it?";
$lang->task->confirmFinish         = '"Left Hour" is 0. Do you want to change the status to "Finished"?';
$lang->task->confirmRecord         = '"Left Hour" is 0. Do you want to set the task as "Finished"?';
$lang->task->confirmTransfer       = '"Left Hour" is 0，Do you want to transfer task?';
$lang->task->noTask                = 'No tasks yet. ';
$lang->task->kanbanDenied          = 'Please create a Kanban first';
$lang->task->createDenied          = "Create Task is denied in this {$lang->projectCommon}";
$lang->task->cannotDeleteParent    = 'Cannot delete parent task';
$lang->task->addChildTask          = 'Because the task has cost hours, ZenTao will create a child task with the same name to record the cost housrs to ensure data consistency.';

$lang->task->error->skipClose       = 'Task: %s is not “Finished” or “Cancelled”. Do you want to close it?';
$lang->task->error->consumed        = 'Task: %s hour must be < 0. Ignore changes to this task.';
$lang->task->error->assignedTo      = 'Multi-user task in the current status cannot be assigned to a member who is not in the task team.';
$lang->task->error->alreadyStarted  = 'You cannot start this task, because it is started.';
$lang->task->error->alreadyConsumed = 'The currently selected parent task has been consumed.';

/* Report. */
$lang->task->report->value = 'Tasks';

$lang->task->report->charts['tasksPerExecution'] = 'Group by ' . $lang->executionCommon . 'Task';
$lang->task->report->charts['tasksPerModule']    = 'Group by Module Task';
$lang->task->report->charts['tasksPerType']      = 'Group by Task Type';
$lang->task->report->charts['tasksPerStatus']    = 'Group by Task Status';
