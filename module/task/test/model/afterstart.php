#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('bug')->gen(5);
zenData('project')->loadYaml('project')->gen(6);
zenData('task')->loadYaml('task')->gen(9);
zenData('taskteam')->loadYaml('taskteam')->gen(6);
zenData('kanbanregion')->loadYaml('kanbanregion')->gen(1);
zenData('kanbanlane')->loadYaml('kanbanlane')->gen(1);
zenData('kanbancolumn')->loadYaml('kanbancolumn')->gen(7);
zenData('kanbancell')->loadYaml('kanbancell')->gen(7);

/**

title=taskModel->afterStart();
timeout=0
cid=18763

- 测试开始任务状态为未开始的任务后的数据处理 @1
- 测试开始任务状态为进行中的任务后的数据处理属性load @1
- 测试开始任务状态为已完成的任务后的数据处理 @1
- 测试开始任务状态为已取消的任务后的数据处理 @1
- 测试开始任务状态为已关闭的任务后的数据处理 @1
- 测试开始任务状态为未开始的子任务后的数据处理 @1
- 测试开始任务状态为未开始的串行任务后的数据处理 @1
- 测试开始任务状态为进行中的并行任务后的数据处理 @1
- 测试给任务增加备注 @1
- 测试更新任务的看板数据 @1

*/

$taskIDList = range(1, 9);

$waitTask   = array('assignedTo' => 'admin', 'consumed' => 10);
$doingTask  = array('assignedTo' => 'user1', 'consumed' => 10, 'status' => 'done');
$doneTask   = array('assignedTo' => '',      'consumed' => 5);
$cancelTask = array('assignedTo' => 'admin', 'consumed' => 0);
$closedTask = array('assignedTo' => 'admin', 'consumed' => 0, 'left' => 5);
$childTask  = array('assignedTo' => 'admin', 'consumed' => 2, 'left' => 5);
$linearTask = array('assignedTo' => 'admin', 'consumed' => 0, 'left' => 5);
$multiTask  = array('assignedTo' => 'admin', 'consumed' => 0);
$output     = array('fromColID' => 1, 'toColID' => 2, 'fromLaneID' => 1, 'toLaneID' => 1);

$taskTester = new taskTest();
$taskTester->objectModel->app->moduleName = 'task';
$taskTester->objectModel->app->rawMethod  = 'start';

r($taskTester->afterStartTest($taskIDList[0], $waitTask))          && p()       && e('1'); // 测试开始任务状态为未开始的任务后的数据处理
r($taskTester->afterStartTest($taskIDList[1], $doingTask))         && p('load') && e('1'); // 测试开始任务状态为进行中的任务后的数据处理
r($taskTester->afterStartTest($taskIDList[2], $doneTask))          && p()       && e('1'); // 测试开始任务状态为已完成的任务后的数据处理
r($taskTester->afterStartTest($taskIDList[3], $cancelTask))        && p()       && e('1'); // 测试开始任务状态为已取消的任务后的数据处理
r($taskTester->afterStartTest($taskIDList[4], $closedTask))        && p()       && e('1'); // 测试开始任务状态为已关闭的任务后的数据处理
r($taskTester->afterStartTest($taskIDList[6], $childTask))         && p()       && e('1'); // 测试开始任务状态为未开始的子任务后的数据处理
r($taskTester->afterStartTest($taskIDList[7], $linearTask))        && p()       && e('1'); // 测试开始任务状态为未开始的串行任务后的数据处理
r($taskTester->afterStartTest($taskIDList[8], $multiTask))         && p()       && e('1'); // 测试开始任务状态为进行中的并行任务后的数据处理
r($taskTester->afterStartTest($taskIDList[0], $waitTask))          && p()       && e('1'); // 测试给任务增加备注
r($taskTester->afterStartTest($taskIDList[0], $waitTask, $output)) && p()       && e('1'); // 测试更新任务的看板数据
