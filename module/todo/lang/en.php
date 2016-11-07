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
$lang->todo->common       = 'To-Dos';
$lang->todo->index        = "Home";
$lang->todo->create       = "Create";
$lang->todo->batchCreate  = "Batch Create";
$lang->todo->edit         = "Edit";
$lang->todo->batchEdit    = "Batch Edit";
$lang->todo->view         = "Info";
$lang->todo->finish       = "Finish";
$lang->todo->batchFinish  = "Batch Finish";
$lang->todo->export       = "Export";
$lang->todo->delete       = "Delete";
$lang->todo->import2Today = "Import to Today";
$lang->todo->import       = "Import";
$lang->todo->legendBasic  = "Basic Info";

$lang->todo->account     = 'Owner';
$lang->todo->date        = 'Date';
$lang->todo->beginAB     = 'Begin';
$lang->todo->endAB       = 'End';
$lang->todo->beginAndEnd = 'Time Frame';
$lang->todo->type        = 'Type';
$lang->todo->pri         = 'Priority';
$lang->todo->name        = 'Name';
$lang->todo->status      = 'Status';
$lang->todo->desc        = 'Description';
$lang->todo->private     = 'Private';

$lang->todo->confirmBug   = 'This To-Do is linked to Bug #%s. Do you want to edit it?';
$lang->todo->confirmTask  = 'This To-Do is linked to Task #%s，Do you want to edit it?';

$lang->todo->statusList['wait']     = 'Pending';
$lang->todo->statusList['doing']    = 'Ongoing';
$lang->todo->statusList['done']     = 'Done';
//$lang->todo->statusList['cancel']   = 'Cancelled';
//$lang->todo->statusList['postpone'] = 'Delayed';

$lang->todo->priList[3] = '3';
$lang->todo->priList[1] = '1';
$lang->todo->priList[2] = '2';
$lang->todo->priList[4] = '4';

$lang->todo->typeList['custom'] = 'Custom';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = $lang->projectCommon . 'Task';

$lang->todo->confirmDelete  = "Are you sure to delete this todo?";
$lang->todo->thisIsPrivate  = 'This is a private todo。:)';
$lang->todo->lblDisableDate = 'Set time lately';

$lang->todo->periods['today']      = 'Today';
$lang->todo->periods['yesterday']  = 'Yesterday';
$lang->todo->periods['thisWeek']   = 'This Week';
$lang->todo->periods['lastWeek']   = 'Last Week';
$lang->todo->periods['thisMonth']  = 'This Month';
$lang->todo->periods['lastmonth']  = 'Last Month';
$lang->todo->periods['thisSeason'] = 'This Season';
$lang->todo->periods['thisYear']   = 'This Year';
$lang->todo->periods['future']     = 'Future';
$lang->todo->periods['before']     = 'Undone';
$lang->todo->periods['all']        = 'All';

$lang->todo->action = new stdclass();
$lang->todo->action->finished  = array('main' => '$date, is finished by <strong>$actor</strong>.');
$lang->todo->action->marked    = array('main' => '$date, is marked by <strong>$actor</strong> as <strong>$extra</strong>.', 'extra' => 'statusList');
