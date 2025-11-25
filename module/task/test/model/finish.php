#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(6);
zenData('task')->loadYaml('task')->gen(9);
zenData('effort')->gen(0);
zenData('taskteam')->loadYaml('taskteam')->gen(6);

/**

title=taskModel->finish();
timeout=0
cid=18787

- 测试完成 任务状态为未开始的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @done
- 测试完成 任务状态为进行中的任务
 - 第1条的field属性 @status
 - 第1条的old属性 @doing
 - 第1条的new属性 @done
- 测试完成 任务状态为已完成的任务
 - 第0条的field属性 @left
 - 第0条的old属性 @2
 - 第0条的new属性 @0
- 测试完成 任务状态为已取消的任务
 - 第1条的field属性 @status
 - 第1条的old属性 @cancel
 - 第1条的new属性 @done
- 测试完成 任务状态为已取消的任务
 - 第1条的field属性 @status
 - 第1条的old属性 @closed
 - 第1条的new属性 @done
- 测试完成 任务状态为未开始的子任务
 - 第1条的field属性 @status
 - 第1条的old属性 @wait
 - 第1条的new属性 @done
- 测试完成 任务状态为未开始的串行任务
 - 第1条的field属性 @status
 - 第1条的old属性 @wait
 - 第1条的new属性 @done
- 测试完成 任务状态为未开始的并行任务
 - 第1条的field属性 @status
 - 第1条的old属性 @doing
 - 第1条的new属性 @done

*/

$taskIDList = range(1, 9);

$waitTask   = array('assignedTo' => 'admin', 'consumed' => 10);
$doingTask  = array('assignedTo' => 'user1', 'consumed' => 10);
$doneTask   = array('assignedTo' => '',      'consumed' => 5);
$cancelTask = array('assignedTo' => 'admin', 'consumed' => 0);
$closedTask = array('assignedTo' => 'admin', 'consumed' => 0, 'left' => 5);
$childTask  = array('assignedTo' => 'admin', 'consumed' => 2, 'left' => 5);
$linearTask = array('assignedTo' => 'admin', 'consumed' => 0, 'left' => 5);
$multiTask  = array('assignedTo' => 'admin', 'consumed' => 0);

$taskTester = new taskTest();

r($taskTester->finishTest($taskIDList[0], $waitTask))   && p('0:field,old,new') && e('status,wait,done');   // 测试完成 任务状态为未开始的任务
r($taskTester->finishTest($taskIDList[1], $doingTask))  && p('1:field,old,new') && e('status,doing,done');  // 测试完成 任务状态为进行中的任务
r($taskTester->finishTest($taskIDList[2], $doneTask))   && p('0:field,old,new') && e('left,2,0');           // 测试完成 任务状态为已完成的任务
r($taskTester->finishTest($taskIDList[3], $cancelTask)) && p('1:field,old,new') && e('status,cancel,done'); // 测试完成 任务状态为已取消的任务
r($taskTester->finishTest($taskIDList[4], $closedTask)) && p('1:field,old,new') && e('status,closed,done');  // 测试完成 任务状态为已取消的任务
r($taskTester->finishTest($taskIDList[6], $childTask))  && p('1:field,old,new') && e('status,wait,done');   // 测试完成 任务状态为未开始的子任务
r($taskTester->finishTest($taskIDList[7], $linearTask)) && p('1:field,old,new') && e('status,wait,done');   // 测试完成 任务状态为未开始的串行任务
r($taskTester->finishTest($taskIDList[8], $multiTask))  && p('1:field,old,new') && e('status,doing,done');  // 测试完成 任务状态为未开始的并行任务