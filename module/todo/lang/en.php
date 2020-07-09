<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 4676 2013-04-26 06:08:23Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->todo->common       = 'Todo';
$lang->todo->index        = "Home";
$lang->todo->create       = "Add Todo";
$lang->todo->createCycle  = "Add Recurred Todo";
$lang->todo->assignTo     = "Assigned To";
$lang->todo->assignedDate = "Assigned Date";
$lang->todo->assignAction = "Assign Todo";
$lang->todo->start        = "Start Todo";
$lang->todo->activate     = "Activate Todo";
$lang->todo->batchCreate  = "Batch Add ";
$lang->todo->edit         = "Edit Todo";
$lang->todo->close        = "Close Todo";
$lang->todo->batchClose   = "Batch Close";
$lang->todo->batchEdit    = "Batch Edit Todos";
$lang->todo->view         = "Todo Detail";
$lang->todo->finish       = "Finish Todo";
$lang->todo->batchFinish  = "Batch Finish";
$lang->todo->export       = "Export Todo";
$lang->todo->delete       = "Delete Todo";
$lang->todo->import2Today = "Import to Today";
$lang->todo->import       = "Import";
$lang->todo->legendBasic  = "Basic Info";
$lang->todo->cycle        = "Recur";
$lang->todo->cycleConfig  = "Recurrence";

$lang->todo->reasonList['story'] = "Convert to Story";
$lang->todo->reasonList['task']  = "Convert to Task";
$lang->todo->reasonList['bug']   = "Convert to Bug";
$lang->todo->reasonList['done']  = "Done";

$lang->todo->id           = 'ID';
$lang->todo->account      = 'Owner';
$lang->todo->date         = 'Date';
$lang->todo->begin        = 'Begin';
$lang->todo->end          = 'End';
$lang->todo->beginAB      = 'Begin';
$lang->todo->endAB        = 'End';
$lang->todo->beginAndEnd  = 'Begin and End';
$lang->todo->idvalue      = 'Link ID';
$lang->todo->type         = 'Type';
$lang->todo->pri          = 'Priority';
$lang->todo->name         = 'Title';
$lang->todo->status       = 'Status';
$lang->todo->desc         = 'Description';
$lang->todo->private      = 'Private';
$lang->todo->cycleDay     = 'Day';
$lang->todo->cycleWeek    = 'Week';
$lang->todo->cycleMonth   = 'Month';
$lang->todo->assignedTo   = 'AssignedTo';
$lang->todo->assignedBy   = 'AssignedBy';
$lang->todo->finishedBy   = 'FinishedBy';
$lang->todo->finishedDate = 'FinishedDate';
$lang->todo->closedBy     = 'ClosedBy';
$lang->todo->closedDate   = 'ClosedDate';
$lang->todo->deadline     = 'Expiration';

$lang->todo->every      = 'Every';
$lang->todo->beforeDays = "<span class='input-group-addon'>Auto create the todo</span>%s<span class='input-group-addon'>days before</span>";
$lang->todo->dayNames   = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');

$lang->todo->confirmBug   = 'This Todo is linked to Bug #%s. Do you want to edit it?';
$lang->todo->confirmTask  = 'This Todo is linked to Task #%s，Do you want to edit it?';
$lang->todo->confirmStory = 'This Todo is linked to Story #%s，Do you want to edit it?';

$lang->todo->statusList['wait']   = 'Waiting';
$lang->todo->statusList['doing']  = 'Doing';
$lang->todo->statusList['done']   = 'Done';
$lang->todo->statusList['closed'] = 'Closed';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[0] = '';
$lang->todo->priList[3] = 'Normal';
$lang->todo->priList[1] = 'Critical';
$lang->todo->priList[2] = 'Important';
$lang->todo->priList[4] = 'Low';

$lang->todo->typeList['custom']   = 'Custom';
$lang->todo->typeList['cycle']    = 'Recur';
$lang->todo->typeList['bug']      = 'Bug';
$lang->todo->typeList['task']     = 'Task';
$lang->todo->typeList['story']    = 'Story';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "Do you want to delete this todo?";
$lang->todo->thisIsPrivate  = 'This is a private todo';
$lang->todo->lblDisableDate = 'TBD';
$lang->todo->lblBeforeDays  = "Create a todo %s day(s) earlier";
$lang->todo->lblClickCreate = "Click to Add Todo";
$lang->todo->noTodo         = 'No todos of this type.';
$lang->todo->noAssignedTo   = "The AssignedTo should not be empty.";
$lang->todo->unfinishedTodo = 'The todos of ID %s are not finished, and can not close.';

$lang->todo->periods['all']        = 'All Todos';
$lang->todo->periods['thisYear']   = 'ThisYear';
$lang->todo->periods['future']     = 'TBD';
$lang->todo->periods['before']     = 'Unfinished';
$lang->todo->periods['cycle']      = 'Recurrence';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, is $extra by <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, is marked by <strong>$actor</strong> as <strong>$extra</strong>.', 'extra' => 'statusList');
