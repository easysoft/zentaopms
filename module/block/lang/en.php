<?php
/**
 * The en file of crm block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
$lang->block = new stdclass();
$lang->block->num      = 'Number';
$lang->block->type     = 'Type';
$lang->block->orderBy  = 'Order';
$lang->block->status   = 'Status';
$lang->block->actions  = 'Action';

$lang->block->availableBlocks = new stdclass();

$lang->block->availableBlocks->todo    = 'My todo';
$lang->block->availableBlocks->task    = 'My task';
$lang->block->availableBlocks->bug     = 'My bug';
$lang->block->availableBlocks->case    = 'My case';
$lang->block->availableBlocks->story   = 'My story';
$lang->block->availableBlocks->product = $lang->productCommon;
$lang->block->availableBlocks->project = $lang->projectCommon;

$lang->block->orderByList = new stdclass();

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID Increment';
$lang->block->orderByList->task['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->task['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->task['pri_desc']      = 'Priority Decreasing';
$lang->block->orderByList->task['estimate_asc']  = 'Estimate Increment';
$lang->block->orderByList->task['estimate_desc'] = 'Estimate Decreasing';
$lang->block->orderByList->task['status_asc']    = 'Status Increment';
$lang->block->orderByList->task['status_desc']   = 'Status Decreasing';
$lang->block->orderByList->task['deadline_asc']  = 'Deadline Increment';
$lang->block->orderByList->task['deadline_desc'] = 'Deadline Decreasing';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID Increment';
$lang->block->orderByList->bug['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->bug['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->bug['pri_desc']      = 'Priority Decreasing';
$lang->block->orderByList->bug['severity_asc']  = 'Severity Increment';
$lang->block->orderByList->bug['severity_desc'] = 'Severity Decreasing';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']        = 'ID Increment';
$lang->block->orderByList->case['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->case['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->case['pri_desc']      = 'Priority Decreasing';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']        = 'ID Increment';
$lang->block->orderByList->story['id_desc']       = 'ID Decreasing';
$lang->block->orderByList->story['pri_asc']       = 'Priority Increment';
$lang->block->orderByList->story['pri_desc']      = 'Priority Decreasing';
$lang->block->orderByList->story['status_asc']    = 'Status Increment';
$lang->block->orderByList->story['status_desc']   = 'Status Decreasing';
$lang->block->orderByList->story['stage_asc']     = 'Stage Increment';
$lang->block->orderByList->story['stage_desc']    = 'Stage Decreasing';

$lang->block->typeList = new stdclass();

$lang->block->typeList->task['assignedTo'] = 'Assigned to';
$lang->block->typeList->task['openedBy']   = 'Opened by';
$lang->block->typeList->task['finishedBy'] = 'Finished by';
$lang->block->typeList->task['closedBy']   = 'Closed by';
$lang->block->typeList->task['canceledBy'] = 'Canceled by';

$lang->block->typeList->bug['assignedTo'] = 'Assigned to';
$lang->block->typeList->bug['openedBy']   = 'Opened by';
$lang->block->typeList->bug['resolvedBy'] = 'Resolved by';
$lang->block->typeList->bug['closedBy']   = 'Closed by';

$lang->block->typeList->case['assigntome'] = 'Assign to me';
$lang->block->typeList->case['openedbyme'] = 'Opened by me';

$lang->block->typeList->story['assignedTo'] = 'Assigned to';
$lang->block->typeList->story['openedBy']   = 'Opened by';
$lang->block->typeList->story['reviewedBy'] = 'Reviewed by';
$lang->block->typeList->story['closedBy']   = 'Closed by';
