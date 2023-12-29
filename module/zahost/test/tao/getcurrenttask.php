#!/usr/bin/env php
<?php

/**

title=测试 zahostTao->getCurrentTask();
timeout=0
cid=1

- 测试有下载中的任务，当前任务为下载中的任务
 - 属性status @inprogress
 - 属性task @1
- 测试如果没有跟当前镜像编号相同的任务，返回 null @0
- 测试没有下载中的任务，其他任务中最后一个为当前任务
 - 属性status @created
 - 属性task @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$zahost = new zahostTest();

$statusGroupTasks = new stdclass();

$task1 = new stdclass();
$task1->status  = 'inprogress';
$task1->task    = 1;
$task1->endDate = '';

$inprogressTasks = array();
$inprogressTasks[] = $task1;

$statusGroupTasks->inprogress = $inprogressTasks;

$imageID = 1;

$zahost = new zahostTest();
r($zahost->getCurrentTask($imageID, $statusGroupTasks)) && p('status,task') && e('inprogress,1'); //测试有下载中的任务，当前任务为下载中的任务

$task1->task = 2;
r($zahost->getCurrentTask($imageID, $statusGroupTasks)) && p() && e('0'); //测试如果没有跟当前镜像编号相同的任务，返回 null

$task1->task    = 1;
$task1->status  = 'canceled';
$task1->endDate = '2023-12-29 13:29:00';

$task2 = new stdclass();
$task2->status  = 'created';
$task2->task    = 1;
$task2->endDate = '2023-12-29 13:30:00';
$inprogressTasks[] = $task2;

$statusGroupTasks->inprogress = $inprogressTasks;

r($zahost->getCurrentTask($imageID, $statusGroupTasks)) && p('status,task') && e('created,1'); //测试没有下载中的任务，其他任务中最后一个为当前任务