#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

$task = zdTable('task');
$task->estimate->range('2,2');
$task->consumed->range('1,2');
$task->left->range('1,0');
$task->status->range('doing,done');
$task->gen(2);

$effort = zdTable('effort');
$effort->objectType->range('task');
$effort->objectID->range('1,2{2}');
$effort->consumed->range('1,1,1');
$effort->left->range('1,1,0');
$effort->deleted->range('0');
$effort->gen(3);

/**
title=获取删除工时后的任务
timeout=0
cid=1
*/

$task = new taskTest();
r($task->getTaskAfterDeleteWorkhourTest(1)) && p('consumed,left,status') && e('0,2,wait');  // 删除ID为1的工时
r($task->getTaskAfterDeleteWorkhourTest(2)) && p('consumed,left,status') && e('1,1,done');  // 删除ID为2的工时
r($task->getTaskAfterDeleteWorkhourTest(3)) && p('consumed,left,status') && e('1,1,doing'); // 删除ID为3的工时
