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
$lang->todo->index        = 'Home';
$lang->todo->create       = 'Add Todo';
$lang->todo->createCycle  = 'Add Recurred Todo';
$lang->todo->assignTo     = 'Assigned To';
$lang->todo->assignedDate = 'Assigned Date';
$lang->todo->assignAction = 'Assign Todo';
$lang->todo->start        = 'Start Todo';
$lang->todo->activate     = 'Activate Todo';
$lang->todo->batchCreate  = 'Batch Add ';
$lang->todo->edit         = 'Edit Todo';
$lang->todo->close        = 'Close Todo';
$lang->todo->batchClose   = 'Batch Close';
$lang->todo->batchEdit    = 'Batch Edit Todos';
$lang->todo->view         = 'Todo Detail';
$lang->todo->finish       = 'Finish Todo';
$lang->todo->batchFinish  = 'Batch Finish';
$lang->todo->export       = 'Export Todo';
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
$lang->todo->transform    = 'Transform';

$lang->todo->reasonList['story'] = 'Convert to Story';
$lang->todo->reasonList['task']  = 'Convert to Task';
$lang->todo->reasonList['bug']   = 'Convert to Bug';
$lang->todo->reasonList['done']  = 'Done';

$lang->todo->id           = 'ID';
$lang->todo->account      = 'Owner';
$lang->todo->date         = 'Date';
$lang->todo->begin        = 'Begin';
$lang->todo->end          = 'End';
$lang->todo->beginAB      = 'Begin';
$lang->todo->endAB        = 'End';
$lang->todo->beginAndEnd  = 'Begin and End';
$lang->todo->objectID     = 'Link ID';
$lang->todo->type         = 'Type';
$lang->todo->pri          = 'Priority';
$lang->todo->name         = 'Title';
$lang->todo->status       = 'Status';
$lang->todo->desc         = 'Description';
$lang->todo->config       = 'Config';
$lang->todo->private      = 'Private';
$lang->todo->cycleDay     = 'Day';
$lang->todo->cycleWeek    = 'Week';
$lang->todo->cycleMonth   = 'Month';
$lang->todo->cycleYear    = 'Year';
$lang->todo->day          = 'Day';
$lang->todo->assignedTo   = 'AssignedTo';
$lang->todo->assignedBy   = 'AssignedBy';
$lang->todo->finishedBy   = 'FinishedBy';
$lang->todo->finishedDate = 'FinishedDate';
$lang->todo->closedBy     = 'ClosedBy';
$lang->todo->closedDate   = 'ClosedDate';
$lang->todo->deadline     = 'Expiration';
$lang->todo->deleted      = 'Deleted';
$lang->todo->ditto        = 'Ditto';
$lang->todo->from         = 'From';
$lang->todo->generate     = 'Generate a todo';
$lang->todo->advance      = 'Advance';
$lang->todo->cycleType    = 'Cycle type';
$lang->todo->monthly      = 'Monthly';
$lang->todo->weekly       = 'Weekly';

$lang->todo->cycleDaysLabel  = 'Interval days';
$lang->todo->beforeDaysLabel = 'Days in advance';

$lang->todo->every        = 'Every';
$lang->todo->specify      = 'Specify';
$lang->todo->everyYear    = 'Annually';
$lang->todo->beforeDays   = "<span class='input-group-addon'>Auto create the todo</span>%s<span class='input-group-addon'>days before</span>";
$lang->todo->dayNames     = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');
$lang->todo->specifiedDay = array(1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);

$lang->todo->confirmBug     = 'This Todo is linked to Bug #%s. Do you want to edit it?';
$lang->todo->confirmTask    = 'This Todo is linked to Task #%s，Do you want to edit it?';
$lang->todo->confirmStory   = 'This Todo is linked to Story #%s，Do you want to edit it?';
$lang->todo->noOptions      = 'You have no %s todo at the moment. Please reselect the Todo type.';
$lang->todo->summary        = 'Total todos: <strong>%s</strong>, Wait: <strong>%s</strong>, Doing: <strong>%s</strong>.';
$lang->todo->checkedSummary = 'Seleted: <strong>%total%</strong>, Wait: <strong>%wait%</strong>, Doing: <strong>%doing%</strong>.';

$lang->todo->abbr = new stdclass();
$lang->todo->abbr->start  = 'Start';
$lang->todo->abbr->finish = 'Finish';

$lang->todo->statusList['wait']   = 'Waiting';
$lang->todo->statusList['doing']  = 'Doing';
$lang->todo->statusList['done']   = 'Done';
$lang->todo->statusList['closed'] = 'Closed';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[1] = 'Critical';
$lang->todo->priList[2] = 'Important';
$lang->todo->priList[3] = 'Normal';
$lang->todo->priList[4] = 'Low';

$lang->todo->typeList['custom']   = 'Custom';
$lang->todo->typeList['cycle']    = 'Recur';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = 'Task';
$lang->todo->typeList['story']    = 'Story';
$lang->todo->typeList['testtask'] = 'Testtask';

$lang->todo->fromList['bug']   = 'Related Bug';
$lang->todo->fromList['task']  = 'Related Task';
$lang->todo->fromList['story'] = 'Related' . $lang->SRCommon;

$lang->todo->confirmDelete  = 'Do you want to delete this todo?';
$lang->todo->thisIsPrivate  = 'This is a private todo';
$lang->todo->lblDisableDate = 'TBD';
$lang->todo->lblBeforeDays  = 'Create a todo %s day(s) earlier';
$lang->todo->lblClickCreate = 'Click to Add Todo';
$lang->todo->noTodo         = 'No todos of this type.';
$lang->todo->noAssignedTo   = 'The AssignedTo should not be empty.';
$lang->todo->unfinishedTodo = 'The todos of ID %s are not finished, and can not close.';
$lang->todo->today          = 'Todo Today';
$lang->todo->selectProduct  = "Please select a {$lang->productCommon}";
$lang->todo->privateTip     = 'Only the todo I create that is assigned to me can be set to private, and only I can see it once it is set to private.';

$lang->todo->periods['all']             = 'Assigned To Yourself';
$lang->todo->periods['before']          = 'Unfinished';
$lang->todo->periods['future']          = 'TBD';
$lang->todo->periods['thisWeek']        = 'This Week';
$lang->todo->periods['thisMonth']       = 'This Month';
$lang->todo->periods['thisYear']        = 'This Year';
$lang->todo->periods['assignedToOther'] = 'Assigned To Other';
$lang->todo->periods['cycle']           = 'Recurrence';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, is $extra by <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, is marked by <strong>$actor</strong> as <strong>$extra</strong>.', 'extra' => 'statusList');
