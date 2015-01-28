<?php
/**
 * The todo module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: en.php 4676 2013-04-26 06:08:23Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->todo->common       = 'TODO';
$lang->todo->index        = "Index";
$lang->todo->create       = "Create";
$lang->todo->batchCreate  = "Batch create";
$lang->todo->edit         = "Edit";
$lang->todo->batchEdit    = "Batch edit";
$lang->todo->view         = "Info";
$lang->todo->viewAB       = "Info";
$lang->todo->finish       = "Finish";
$lang->todo->batchFinish  = "Batch finish";
$lang->todo->export       = "Export";
$lang->todo->delete       = "Delete";
$lang->todo->browse       = "Browse";
$lang->todo->import2Today = "Import to today";
$lang->todo->import       = "Import to";
$lang->todo->changeStatus = "Change";
$lang->todo->legendBasic  = "Basic Info";

$lang->todo->id          = 'ID';
$lang->todo->account     = 'Owner';
$lang->todo->date        = 'Date';
$lang->todo->begin       = 'Begin time';
$lang->todo->beginAB     = 'Begin';
$lang->todo->end         = 'End time';
$lang->todo->endAB       = 'End';
$lang->todo->beginAndEnd = 'Begin and End';
$lang->todo->type        = 'Type';
$lang->todo->pri         = 'Priority';
$lang->todo->name        = 'Name';
$lang->todo->status      = 'Status';
$lang->todo->desc        = 'Desc';
$lang->todo->private     = 'Private';
$lang->todo->idvalue     = 'Task or bug';

$lang->todo->confirmBug   = 'This todo linked to bug #%s，chang it also?';
$lang->todo->confirmTask  = 'This todo linked to task #%s，chang it also?';

$lang->todo->statusList['wait']     = 'Waiting';
$lang->todo->statusList['doing']    = 'Doing';
$lang->todo->statusList['done']     = 'Done';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '3';
$lang->todo->priList[1] = '1';
$lang->todo->priList[2] = '2';
$lang->todo->priList[4] = '4';

$lang->todo->typeList['custom'] = 'Custom';
$lang->todo->typeList['bug']    = 'Bug';
$lang->todo->typeList['task']   = 'Task';

$lang->todo->confirmDelete  = "Are you sure to delete this todo?";
$lang->todo->successMarked  = "Successfully changed status";;
$lang->todo->thisIsPrivate  = 'This is a private todo。:)';
$lang->todo->lblDisableDate = 'Set time lately';

$lang->todo->periods['today']      = 'Today';
$lang->todo->periods['yesterday']  = 'Yesterday';
$lang->todo->periods['thisWeek']   = 'This week';
$lang->todo->periods['lastWeek']   = 'Last week';
$lang->todo->periods['thisMonth']  = 'This month';
$lang->todo->periods['lastmonth']  = 'Last month';
$lang->todo->periods['thisSeason'] = 'This season';
$lang->todo->periods['thisYear']   = 'This year';
$lang->todo->periods['future']     = 'Future';
$lang->todo->periods['before']     = 'Undone';
$lang->todo->periods['all']        = 'All';

$lang->todo->action = new stdclass();
$lang->todo->action->finished = array('main' => '$date, Finished by <strong>$actor</strong>');
$lang->todo->action->marked   = array('main' => '$date, Change status to <stong>$extra</strong> by <strong>$actor</strong>。', 'extra' => $lang->todo->statusList);

