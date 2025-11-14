#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(6);
zenData('task')->loadYaml('task')->gen(6);

/**

title=taskModel->afterChangeStatus();
timeout=0
cid=18760

- 测试任务状态为未开始的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @done
- 测试任务状态为进行中的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @changed
 - 第0条的new属性 @done
- 测试任务状态为已完成的任务 @0
- 测试任务状态为已完成的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @pause
 - 第0条的new属性 @done
- 测试任务状态为已取消的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @cancel
 - 第0条的new属性 @done
- 测试任务状态为已关闭的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @done

*/

$taskIDList = range(1, 6);
$taskTester = new taskTest();

r($taskTester->afterChangeStatusTest($taskIDList[0], 'done')) && p('0:field,old,new') && e('status,wait,done');    // 测试任务状态为未开始的任务
r($taskTester->afterChangeStatusTest($taskIDList[1], 'done')) && p('0:field,old,new') && e('status,changed,done'); // 测试任务状态为进行中的任务
r($taskTester->afterChangeStatusTest($taskIDList[2], 'done')) && p()                  && e('0');                   // 测试任务状态为已完成的任务
r($taskTester->afterChangeStatusTest($taskIDList[3], 'done')) && p('0:field,old,new') && e('status,pause,done');   // 测试任务状态为已完成的任务
r($taskTester->afterChangeStatusTest($taskIDList[4], 'done')) && p('0:field,old,new') && e('status,cancel,done');  // 测试任务状态为已取消的任务
r($taskTester->afterChangeStatusTest($taskIDList[5], 'done')) && p('0:field,old,new') && e('status,closed,done');  // 测试任务状态为已关闭的任务