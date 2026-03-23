<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 4676 2013-04-26 06:08:23Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
global $config;
$lang->todo->index        = 'Home';
$lang->todo->create       = 'Add Todo';
$lang->todo->createCycle  = 'Add Recurring Todo';
$lang->todo->assignTo     = 'Assigned to';
$lang->todo->assignedDate = 'Assigned Date';
$lang->todo->assignAction = 'Assign Todo';
$lang->todo->start        = 'Start Todo';
$lang->todo->activate     = 'Activate Todo';
$lang->todo->batchCreate  = 'Batch Add ';
$lang->todo->edit         = 'Edit Todo';
$lang->todo->close        = 'Close Todo';
$lang->todo->batchClose   = 'Batch Close';
$lang->todo->batchEdit    = 'Batch Edit Todos';
$lang->todo->view         = 'Todo Details';
$lang->todo->finish       = 'Complete Todo';
$lang->todo->batchFinish  = 'Batch Complete';
$lang->todo->export       = 'Export Todos';
$lang->todo->delete       = 'Delete Todo';
$lang->todo->import2Today = 'Change Date';
$lang->todo->import       = 'Import';
$lang->todo->legendBasic  = 'Basic Info';
$lang->todo->cycle        = 'Recur';
$lang->todo->cycleConfig  = 'Recurrence';
$lang->todo->project      = $lang->projectCommon;
$lang->todo->product      = $lang->productCommon;
$lang->todo->execution    = $lang->executionCommon;
$lang->todo->changeDate   = 'Change Date';
$lang->todo->future       = 'TBD';
$lang->todo->timespanTo   = 'To';
$lang->todo->transform    = 'Convert to';

$lang->todo->reasonList['story'] = 'Convert to Story';
$lang->todo->reasonList['task']  = 'Convert to Task';
$lang->todo->reasonList['bug']   = 'Convert to Bug';
$lang->todo->reasonList['done']  = 'Done';

$lang->todo->id           = 'ID';
$lang->todo->idAB         = 'ID';
$lang->todo->account      = 'Creator';
$lang->todo->date         = 'Date';
$lang->todo->begin        = 'Start';
$lang->todo->end          = 'End';
$lang->todo->beginAB      = 'Start';
$lang->todo->endAB        = 'End';
$lang->todo->beginAndEnd  = 'Duration';
$lang->todo->objectID     = 'Linked ID';
$lang->todo->type         = 'Type';
$lang->todo->pri          = 'Priority';
$lang->todo->name         = 'Title';
$lang->todo->status       = 'Status';
$lang->todo->desc         = 'Description';
$lang->todo->config       = 'Config';
$lang->todo->private      = 'Private';
$lang->todo->cycleDay     = 'days';
$lang->todo->cycleWeek    = 'Week';
$lang->todo->cycleMonth   = 'Month';
$lang->todo->cycleYear    = 'Year';
$lang->todo->day          = 'Day';
$lang->todo->assignedTo   = 'Assigned to';
$lang->todo->assignedBy   = 'Assigned by';
$lang->todo->finishedBy   = 'Completed by';
$lang->todo->finishedDate = 'Completion Time';
$lang->todo->closedBy     = 'Closed by';
$lang->todo->closedDate   = 'Close Date';
$lang->todo->deadline     = 'Due Date';
$lang->todo->deleted      = 'Deleted';
$lang->todo->ditto        = 'Ditto';
$lang->todo->from         = 'From';
$lang->todo->generate     = 'Create Todo';
$lang->todo->advance      = 'In advance';
$lang->todo->cycleType    = 'Recurring per';
$lang->todo->monthly      = 'Per Month';
$lang->todo->weekly       = 'Per Week';

$lang->todo->cycleDaysLabel  = 'Interval days';
$lang->todo->beforeDaysLabel = 'Days in advance';

$lang->todo->every        = 'Every';
$lang->todo->specify      = 'specified';
$lang->todo->everyYear    = 'Per Year';
$lang->todo->beforeDays   = "<span class='input-group-addon'>Auto create the Todo</span>%s<span class='input-group-addon'>days in advance.</span>";
$lang->todo->dayNames     = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');
$lang->todo->specifiedDay = array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

$lang->todo->confirmBug         = 'This Todo is linked to Bug #%s. Do you want to update it?';
$lang->todo->confirmTask        = 'This Todo is linked to Task #%s，Do you want to update it?';
$lang->todo->confirmStory       = 'This Todo is linked to Story #%s，Do you want to update it?';
$lang->todo->confirmEpic        = $lang->todo->confirmStory;
$lang->todo->confirmRequirement = $lang->todo->confirmStory;
$lang->todo->noOptions          = 'You have no %s Todo at the moment. Please reselect another Todo type.';
$lang->todo->summary            = 'Total Todos: <strong>%s</strong>, Waiting: <strong>%s</strong>, Doing: <strong>%s</strong>.';
$lang->todo->checkedSummary     = 'Seleted Todos: <strong>%total%</strong>, Waiting: <strong>%wait%</strong>, Doing: <strong>%doing%</strong>';

$lang->todo->abbr = new stdclass();
$lang->todo->abbr->start  = 'Start';
$lang->todo->abbr->finish = 'Complete';

$lang->todo->statusList['wait']   = 'Waiting';
$lang->todo->statusList['doing']  = 'Doing';
$lang->todo->statusList['done']   = 'Done';
$lang->todo->statusList['closed'] = 'Closed';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[1] = 'Urgent';
$lang->todo->priList[2] = 'High';
$lang->todo->priList[3] = 'Normal';
$lang->todo->priList[4] = 'Low';

$lang->todo->typeList['custom']      = 'Customize';
$lang->todo->typeList['cycle']       = 'Recurring';
$lang->todo->typeList['bug']         = 'Bug';
$lang->todo->typeList['task']        = 'Task';
$lang->todo->typeList['story']       = 'Story';
if($config->enableER) $lang->todo->typeList['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->todo->typeList['requirement'] = $lang->URCommon;
$lang->todo->typeList['testtask']    = 'Test Request';

$lang->todo->fromList['bug']   = 'Related Bug';
$lang->todo->fromList['task']  = 'Related Bug';
$lang->todo->fromList['story'] = 'Related' . $lang->SRCommon;

$lang->todo->confirmDelete  = 'Are you sure you want to delete this Todo?';
$lang->todo->thisIsPrivate  = 'This is a private Todo.';
$lang->todo->lblDisableDate = 'TBD';
$lang->todo->lblBeforeDays  = 'Create the Todo %s day(s) in advance.';
$lang->todo->lblClickCreate = 'Click to Add Todo';
$lang->todo->noTodo         = 'No Todos available for this type.';
$lang->todo->noAssignedTo   = 'Assignee cannot be empty.';
$lang->todo->unfinishedTodo = 'Todo D%s is not completed and cannot be closed.';
$lang->todo->today          = 'Todo for Today';
$lang->todo->selectProduct  = "Please select a {$lang->productCommon}.";
$lang->todo->privateTip     = 'Only Todos that I created and assigned to myself can be set as private. Once private, they are visible only to me.';

$lang->todo->periods['all']             = 'Assigned to Me';
$lang->todo->periods['before']          = 'Uncompleted';
$lang->todo->periods['future']          = 'TBD';
$lang->todo->periods['thisWeek']        = 'This Week';
$lang->todo->periods['thisMonth']       = 'This Month';
$lang->todo->periods['thisYear']        = 'This Year';
$lang->todo->periods['assignedToOther'] = 'Assigned to Others';
$lang->todo->periods['cycle']           = 'Recurrence';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, is $extra by <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, is marked by <strong>$actor</strong> as <strong>$extra</strong>.', 'extra' => 'statusList');
