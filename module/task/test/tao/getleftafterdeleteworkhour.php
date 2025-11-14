#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

$task = zenData('task');
$task->estimate->range('2,2');
$task->consumed->range('1,2');
$task->left->range('1,0');

$task->status->range('doing,done');
$task->gen(2);

$effort = zenData('effort');
$effort->objectType->range('task');
$effort->objectID->range('1,2{2}');
$effort->consumed->range('1,1,1');
$effort->left->range('1,1,0');
$effort->deleted->range('0');
$effort->gen(3);

/**

title=获取删除工时后的剩余工时
timeout=0
cid=18879

- 删除ID为1的工时
 - 属性taskEstimate @2
 - 属性taskConsumed @1
 - 属性taskLeft @1
 - 属性effortConsumed @1
 - 属性effortLeft @1
 - 属性left @1
- 删除ID为2的工时
 - 属性taskEstimate @2
 - 属性taskConsumed @2
 - 属性taskLeft @0
 - 属性effortConsumed @1
 - 属性effortLeft @1
 - 属性left @0
- 删除ID为3的工时
 - 属性taskEstimate @2
 - 属性taskConsumed @2
 - 属性taskLeft @0
 - 属性effortConsumed @1
 - 属性effortLeft @0
 - 属性left @1

*/

$task = new taskTest();
r($task->getLeftAfterDeleteWorkhourTest(1)) && p('taskEstimate,taskConsumed,taskLeft,effortConsumed,effortLeft,left') && e('2,1,1,1,1,1'); // 删除ID为1的工时
r($task->getLeftAfterDeleteWorkhourTest(2)) && p('taskEstimate,taskConsumed,taskLeft,effortConsumed,effortLeft,left') && e('2,2,0,1,1,0'); // 删除ID为2的工时
r($task->getLeftAfterDeleteWorkhourTest(3)) && p('taskEstimate,taskConsumed,taskLeft,effortConsumed,effortLeft,left') && e('2,2,0,1,0,1'); // 删除ID为3的工时