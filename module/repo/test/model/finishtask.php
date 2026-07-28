#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->finishTask();
timeout=0
cid=18044

- 正常任务调用返回非空 >> 1
- 带团队任务调用返回非空 >> 1
- 零工时任务调用返回非空 >> 1
- 无效参数null不影响结果 >> 1
- 方法存在且可调用 >> 1

*/

$tester->dao->delete()->from(TABLE_TASK)->where('id')->in('1,2,3,4')->exec();

$now   = helper::now();
$tasks = array(
    array('id' => 1, 'project' => 1, 'execution' => 1, 'path' => ',1,', 'name' => '任务1', 'type' => 'devel', 'mode' => '', 'estimate' => 8.00, 'consumed' => 5.00, 'left' => 3.00, 'status' => 'doing', 'openedBy' => 'admin', 'openedDate' => $now, 'assignedTo' => 'admin', 'assignedDate' => $now, 'realStarted' => '2024-01-01 09:00:00', 'vision' => 'rnd', 'deleted' => 0),
    array('id' => 2, 'project' => 1, 'execution' => 1, 'path' => ',2,', 'name' => '任务2', 'type' => 'devel', 'mode' => '', 'estimate' => 8.00, 'consumed' => 5.00, 'left' => 3.00, 'status' => 'doing', 'openedBy' => 'admin', 'openedDate' => $now, 'assignedTo' => 'admin', 'assignedDate' => $now, 'realStarted' => '2024-01-01 09:00:00', 'vision' => 'rnd', 'deleted' => 0),
    array('id' => 3, 'project' => 1, 'execution' => 1, 'path' => ',3,', 'name' => '任务3', 'type' => 'devel', 'mode' => '', 'estimate' => 8.00, 'consumed' => 0.00, 'left' => 0.00, 'status' => 'doing', 'openedBy' => 'admin', 'openedDate' => $now, 'assignedTo' => 'admin', 'assignedDate' => $now, 'realStarted' => '2024-01-01 09:00:00', 'vision' => 'rnd', 'deleted' => 0),
    array('id' => 4, 'project' => 1, 'execution' => 1, 'path' => ',4,', 'name' => '任务4', 'type' => 'devel', 'mode' => '', 'estimate' => 8.00, 'consumed' => 5.00, 'left' => 1.00, 'status' => 'doing', 'openedBy' => 'admin', 'openedDate' => $now, 'assignedTo' => 'admin', 'assignedDate' => $now, 'realStarted' => '2024-01-01 09:00:00', 'vision' => 'rnd', 'deleted' => 0),
);
foreach($tasks as $taskData) $tester->dao->insert(TABLE_TASK)->data((object)$taskData)->exec();

$repoTest = new repoModelTest();

$task = new stdclass();
$task->id = 1;
$task->name = '任务1';
$task->consumed = 5;
$task->left = 3;
$task->openedBy = 'admin';
$task->realStarted = '2024-01-01 09:00:00';
$task->team = '';
$task->mode = '';

$params = array('consumed' => 2, 'left' => 1);
$action = new stdclass(); $action->id = 1; $action->action = 'commit'; $action->extra = 'abc';
$changes = array();

r($repoTest->finishTaskTest($task, $params, $action, $changes)) && p('id') && e('1');

$task2 = clone $task; $task2->id = 2; $task2->team = 'team1';
r($repoTest->finishTaskTest($task2, $params, $action, $changes)) && p('id') && e('2');

$task3 = clone $task; $task3->id = 3; $task3->consumed = 0; $task3->left = 0;
r($repoTest->finishTaskTest($task3, $params, $action, $changes)) && p('id') && e('3');

$task4 = clone $task; $task4->id = 4; $task4->left = 1;
r($repoTest->finishTaskTest(null, $params, $action, $changes)) && p() && e('0');

r($repoTest->finishTaskTest($task4, $params, $action, $changes)) && p('id') && e('4');
