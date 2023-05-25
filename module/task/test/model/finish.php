#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project')->gen(6);
zdTable('task')->config('task')->gen(9);
zdTable('taskteam')->config('taskteam')->gen(6);

/**

title=taskModel->finish();
timeout=0
cid=1

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

r($taskTester->finishTest($taskIDList[0], $waitTask))   && p('0:field,old,new') && e('status,wait,done');    // 测试完成 任务状态为未开始的任务
r($taskTester->finishTest($taskIDList[1], $doingTask))  && p('2:field,old,new') && e('assignedTo,~~,user1'); // 测试完成 任务状态为进行中的任务
r($taskTester->finishTest($taskIDList[2], $doneTask))   && p('0:field,old,new') && e('left,2,0');            // 测试完成 任务状态为已完成的任务
r($taskTester->finishTest($taskIDList[3], $cancelTask)) && p('3:field,old,new') && e('assignedTo,~~,admin'); // 测试完成 任务状态为已取消的任务
r($taskTester->finishTest($taskIDList[4], $closedTask)) && p('0:field,old,new') && e('left,4,5');            // 测试完成 任务状态为已取消的任务
r($taskTester->finishTest($taskIDList[6], $childTask))  && p('0:field,old,new') && e('left,6,5');            // 测试完成 任务状态为未开始的子任务
r($taskTester->finishTest($taskIDList[7], $linearTask)) && p('0:field,old,new') && e('left,7,5');            // 测试完成 任务状态为未开始的串行任务
r($taskTester->finishTest($taskIDList[8], $multiTask))  && p('0:field,old,new') && e('left,8,0');            // 测试完成 任务状态为未开始的并行任务
