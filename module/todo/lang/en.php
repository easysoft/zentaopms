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
$lang->todo->create       = "Create Todo";
$lang->todo->createCycle  = "Create Cycle Todo";
$lang->todo->assignTo     = "Assign";
$lang->todo->activate     = "Activate";
$lang->todo->batchCreate  = "Batch Create";
$lang->todo->edit         = "Edit";
$lang->todo->close        = "Close";
$lang->todo->batchEdit    = "Batch Edit";
$lang->todo->view         = "Overview";
$lang->todo->finish       = "Finish";
$lang->todo->batchFinish  = "Batch Finish";
$lang->todo->export       = "Export";
$lang->todo->delete       = "Delete";
$lang->todo->import2Today = "Import to Today";
$lang->todo->import       = "Import";
$lang->todo->legendBasic  = "Basic Info";
$lang->todo->cycle        = "Cycle";
$lang->todo->cycleConfig  = "Cycle Config";

$lang->todo->reasonList['story'] = "Transfer Story";
$lang->todo->reasonList['task']  = "Transfer Task";
$lang->todo->reasonList['bug']   = "Transfer Bug";
$lang->todo->reasonList['done']  = "Done";

$lang->todo->id          = 'ID';
$lang->todo->account     = 'Owner';
$lang->todo->date        = 'Date';
$lang->todo->begin       = 'Begin';
$lang->todo->end         = 'End';
$lang->todo->beginAB     = 'Begin';
$lang->todo->endAB       = 'End';
$lang->todo->beginAndEnd = 'Duration';
$lang->todo->idvalue     = 'Link ID';
$lang->todo->type        = 'Type';
$lang->todo->pri         = 'Priority';
$lang->todo->name        = 'Todo Name';
$lang->todo->status      = 'Status';
$lang->todo->desc        = 'Description';
$lang->todo->private     = 'Private';
$lang->todo->cycleDay    = 'Day';
$lang->todo->cycleWeek   = 'Week';
$lang->todo->cycleMonth  = 'Month';
$lang->todo->deadline    = 'Deadline';

$lang->todo->every      = 'Every';
$lang->todo->beforeDays = "%s<span class='input-group-addon'>early in advance to be done</span>";
$lang->todo->dayNames   = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');

$lang->todo->confirmBug   = 'This Todo is related to Bug #%s. Do you want to edit it?';
$lang->todo->confirmTask  = 'This Todo is related to Task #%s，Do you want to edit it?';
$lang->todo->confirmStory = 'This Todo is related to Story #%s，Do you want to edit it?';

$lang->todo->statusList['wait']   = 'Wait';
$lang->todo->statusList['doing']  = 'Doing';
$lang->todo->statusList['done']   = 'Done';
$lang->todo->statusList['closed'] = 'Closed';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[3] = 'Normal';
$lang->todo->priList[1] = 'Highest';
$lang->todo->priList[2] = 'High';
$lang->todo->priList[4] = 'Low';
$lang->todo->priList[0] = '';

$lang->todo->typeList['custom'] = 'Custom';
$lang->todo->typeList['cycle']  = 'Periodic';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = $lang->projectCommon . 'Task';
$lang->todo->typeList['story']  = $lang->projectCommon . 'Story';

global $config;
if($config->global->flow == 'onlyTest' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['task']);
if($config->global->flow == 'onlyTask' or $config->global->flow == 'onlyStory') unset($lang->todo->typeList['bug']);

$lang->todo->confirmDelete  = "Are you sure to delete this Todo?";
$lang->todo->thisIsPrivate  = 'This is a private Todo';
$lang->todo->lblDisableDate = 'Set later.';
$lang->todo->lblBeforeDays  = "%s early in advance to be done";
$lang->todo->noTodo         = 'No this type of Todo.';
$lang->todo->noAssignedTo   = "The AssignedTo user can't be empty.";

$lang->todo->periods['today']      = 'Today';
$lang->todo->periods['yesterday']  = 'Yesterday';
$lang->todo->periods['thisWeek']   = 'ThisWeek';
$lang->todo->periods['lastWeek']   = 'LastWeek';
$lang->todo->periods['thisMonth']  = 'ThisMonth';
$lang->todo->periods['lastmonth']  = 'LastMonth';
$lang->todo->periods['thisSeason'] = 'ThisSeason';
$lang->todo->periods['thisYear']   = 'ThisYear';
$lang->todo->periods['future']     = 'Pending';
$lang->todo->periods['before']     = 'Unfinished';
$lang->todo->periods['all']        = 'All';
$lang->todo->periods['cycle']      = 'Cycle';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, is $extra by <strong>$actor</strong>.', 'extra' => 'reasonList');
$lang->todo->action->marked   = array('main' => '$date, is marked by <strong>$actor</strong> as <strong>$extra</strong>.', 'extra' => 'statusList');
