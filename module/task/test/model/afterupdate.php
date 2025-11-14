#!/usr/bin/env php
<?php
/**

title=测试taskModel->afterUpdate();
timeout=0
cid=18764

- 测试子任务的相关属性是否变更。
 - 属性name @开发任务12
 - 属性execution @106
 - 属性module @21
 - 属性project @60
- 测试非团队任务的taskteam表数据是否被删除。 @0
- 测试子任务的相关属性是否变更。属性path @,1,2,
- 测试子任务的相关属性是否变更。属性path @,1,2,3,
- 测试父任务的状态是否跟随子任务变化。属性status @done
- 测试顶级任务的状态是否跟随子任务变化。属性status @done

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$taskData = zenData('task');
$taskData->mode->range('single,linear');
$taskData->parent->range('0,1,2,3,4,5,6,7,8,9');
$taskData->gen(10);
zenData('project')->loadYaml('execution')->gen(10);
zenData('taskteam')->gen(10);
su('admin');

global $tester;
$tester->loadModel('task');

$oldTask = $tester->task->fetchByID(1);
$task    = clone $oldTask;
$task->execution = 106;
$tester->task->afterUpdate($oldTask, $task);

$task = $tester->task->fetchByID(2);
r($task) && p('name,execution,module,project') && e('开发任务12,106,21,60'); // 测试子任务的相关属性是否变更。

$taskTeam = $tester->task->fetchByID(1, 'taskTeam');
r($taskTeam) && p('') && e('0'); // 测试非团队任务的taskteam表数据是否被删除。

$oldTask = $tester->task->fetchByID(2);
$task    = clone $oldTask;
$tester->task->afterUpdate($oldTask, $task);

$newTask = $tester->task->fetchByID(2);
r($newTask) && p('path', ';') && e(',1,2,'); // 测试子任务的相关属性是否变更。

$oldTask = $tester->task->fetchByID(3);
$task    = clone $oldTask;
$tester->task->afterUpdate($oldTask, $task);

$task = $tester->task->fetchByID(3);
r($task) && p('path', ';') && e(',1,2,3,'); // 测试子任务的相关属性是否变更。
$task = $tester->task->fetchByID(2);
r($task) && p('status') && e('done');       // 测试父任务的状态是否跟随子任务变化。
$task = $tester->task->fetchByID(1);
r($task) && p('status') && e('done');       // 测试顶级任务的状态是否跟随子任务变化。
