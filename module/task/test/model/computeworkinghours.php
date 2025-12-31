#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task_computeworkinghours')->gen(15);

/**

title=taskModel->computeWorkingHours();
timeout=0
cid=18778

- 根据父taskID更新普通任务的任务工时
 - 属性id @1
 - 属性estimate @2.00
 - 属性consumed @0.00
 - 属性left @1.00
- 根据父taskID更新任务工时
 - 属性id @2
 - 属性estimate @9.00
 - 属性consumed @9.00
 - 属性left @5.00
- 根据父taskID更新父任务取消的任务工时
 - 属性id @3
 - 属性estimate @9.00
 - 属性consumed @8.00
 - 属性left @4.00
- 根据父taskID更新子任务全部关闭的父任务的工时
 - 属性id @4
 - 属性estimate @5.00
 - 属性consumed @7.00
 - 属性left @0.00
- 根据父taskID更新子任务全部取消的父任务的工时
 - 属性id @5
 - 属性estimate @3.00
 - 属性consumed @0.00
 - 属性left @1.00
- 根据父taskID更新没有子任务的任务工时
 - 属性id @6
 - 属性estimate @4.00
 - 属性consumed @0.00
 - 属性left @1.00
- 根据不存在的taskID计算工时
 - 属性id @0
 - 属性estimate @0
 - 属性consumed @0
 - 属性left @0

*/

$taskIDList = array(1, 2, 3, 4, 5, 6, 1001);

$task = new taskTest();
r($task->computeWorkingHoursTest($taskIDList[0])) && p('id,estimate,consumed,left') && e('1,2.00,0.00,1.00'); // 根据父taskID更新普通任务的任务工时
r($task->computeWorkingHoursTest($taskIDList[1])) && p('id,estimate,consumed,left') && e('2,9.00,9.00,5.00'); // 根据父taskID更新任务工时
r($task->computeWorkingHoursTest($taskIDList[2])) && p('id,estimate,consumed,left') && e('3,9.00,8.00,4.00'); // 根据父taskID更新父任务取消的任务工时
r($task->computeWorkingHoursTest($taskIDList[3])) && p('id,estimate,consumed,left') && e('4,5.00,7.00,0.00'); // 根据父taskID更新子任务全部关闭的父任务的工时
r($task->computeWorkingHoursTest($taskIDList[4])) && p('id,estimate,consumed,left') && e('5,3.00,0.00,1.00'); // 根据父taskID更新子任务全部取消的父任务的工时
r($task->computeWorkingHoursTest($taskIDList[5])) && p('id,estimate,consumed,left') && e('6,4.00,0.00,1.00'); // 根据父taskID更新没有子任务的任务工时
r($task->computeWorkingHoursTest($taskIDList[6])) && p('id,estimate,consumed,left') && e('0,0,0,0');          // 根据不存在的taskID计算工时
