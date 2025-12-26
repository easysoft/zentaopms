#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(6);
zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(6);

/**

title=taskModel->start();
timeout=0
cid=18844

- 测试开始任务状态为未开始的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @doing
- 测试开始任务状态为进行中的任务
 - 第2条的field属性 @consumed
 - 第2条的old属性 @4.00
 - 第2条的new属性 @10
- 测试开始任务状态为已完成的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @done
 - 第0条的new属性 @doing
- 测试开始任务状态为已取消的任务
 - 第3条的field属性 @consumed
 - 第3条的old属性 @6.00
 - 第3条的new属性 @0
- 测试开始任务状态为已取消的任务
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing
- 测试开始任务状态为未开始的子任务
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @doing
- 测试开始任务状态为未开始的串行任务
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @doing
- 测试开始任务状态为未开始的并行任务
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @done

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

r($taskTester->startTest($taskIDList[0], $waitTask))   && p('0:field,old,new') && e('status,wait,doing');   // 测试开始任务状态为未开始的任务
r($taskTester->startTest($taskIDList[1], $doingTask))  && p('2:field,old,new') && e('consumed,4.00,10');    // 测试开始任务状态为进行中的任务
r($taskTester->startTest($taskIDList[2], $doneTask))   && p('0:field,old,new') && e('status,done,doing');   // 测试开始任务状态为已完成的任务
r($taskTester->startTest($taskIDList[3], $cancelTask)) && p('3:field,old,new') && e('consumed,6.00,0');     // 测试开始任务状态为已取消的任务
r($taskTester->startTest($taskIDList[4], $closedTask)) && p('0:field,old,new') && e('status,closed,doing'); // 测试开始任务状态为已取消的任务
r($taskTester->startTest($taskIDList[6], $childTask))  && p('0:field,old,new') && e('status,wait,doing');   // 测试开始任务状态为未开始的子任务
r($taskTester->startTest($taskIDList[7], $linearTask)) && p('0:field,old,new') && e('status,wait,doing');   // 测试开始任务状态为未开始的串行任务
r($taskTester->startTest($taskIDList[8], $multiTask))  && p('0:field,old,new') && e('status,doing,done');   // 测试开始任务状态为未开始的并行任务
