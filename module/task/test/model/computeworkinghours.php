#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task_computeworkinghours')->gen(15);

/**

title=taskModel->computeWorkingHours();
timeout=0
cid=1

- 根据父taskID更新普通任务的任务工时
 - 属性id @1
 - 属性estimate @2
 - 属性consumed @0
 - 属性left @1
- 根据父taskID更新任务工时
 - 属性id @2
 - 属性estimate @9
 - 属性consumed @9
 - 属性left @5
- 根据父taskID更新父任务取消的任务工时
 - 属性id @3
 - 属性estimate @9
 - 属性consumed @8
 - 属性left @4
- 根据父taskID更新子任务全部关闭的父任务的工时
 - 属性id @4
 - 属性estimate @5
 - 属性consumed @7
 - 属性left @0
- 根据父taskID更新子任务全部取消的父任务的工时
 - 属性id @5
 - 属性estimate @3
 - 属性consumed @0
 - 属性left @1
- 根据父taskID更新没有子任务的任务工时
 - 属性id @6
 - 属性estimate @4
 - 属性consumed @0
 - 属性left @1
- 根据不存在的taskID计算工时
 - 属性id @0
 - 属性estimate @0
 - 属性consumed @0
 - 属性left @0

*/

$taskIDList = array(1, 2, 3, 4, 5, 6, 1001);

$task = new taskTest();
r($task->computeWorkingHoursTest($taskIDList[0])) && p('id,estimate,consumed,left') && e('1,2,0,1'); //根据父taskID更新普通任务的任务工时
r($task->computeWorkingHoursTest($taskIDList[1])) && p('id,estimate,consumed,left') && e('2,9,9,5'); //根据父taskID更新任务工时
r($task->computeWorkingHoursTest($taskIDList[2])) && p('id,estimate,consumed,left') && e('3,9,8,4'); //根据父taskID更新父任务取消的任务工时
r($task->computeWorkingHoursTest($taskIDList[3])) && p('id,estimate,consumed,left') && e('4,5,7,0'); //根据父taskID更新子任务全部关闭的父任务的工时
r($task->computeWorkingHoursTest($taskIDList[4])) && p('id,estimate,consumed,left') && e('5,3,0,1'); //根据父taskID更新子任务全部取消的父任务的工时
r($task->computeWorkingHoursTest($taskIDList[5])) && p('id,estimate,consumed,left') && e('6,4,0,1'); //根据父taskID更新没有子任务的任务工时
r($task->computeWorkingHoursTest($taskIDList[6])) && p('id,estimate,consumed,left') && e('0,0,0,0'); //根据不存在的taskID计算工时
