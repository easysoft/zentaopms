#!/usr/bin/env php
<?php

/**

title=- 测试关联需求任务处理 >> 期望story字段包含需求标题 @用户需求1(
timeout=0
cid=1

- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @Task 1
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的story属性 @用户需求1(#1)
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的fromBug属性 @#1 BUG1
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @[多人] Multi task
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的name属性 @[父] Parent task
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的progress属性 @0%
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的progress属性 @30%
- 执行taskTest模块的processExportDataTest方法，参数是array 第0条的progress属性 @100%

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

zenData('user')->loadYaml('user', false, 2)->gen(10);
zenData('project')->loadYaml('project', false, 4)->gen(10);
zenData('story')->loadYaml('story', false, 4)->gen(10);
zenData('bug')->loadYaml('bug', false, 1)->gen(10);
zenData('module')->loadYaml('module', false, 1)->gen(10);
zenData('file')->loadYaml('file', false, 1)->gen(5);

su('admin');

$taskTest = new taskZenTest();

$task1 = new stdClass();
$task1->id = 1;
$task1->project = 1;
$task1->execution = 1;
$task1->module = 1;
$task1->story = 0;
$task1->fromBug = 0;
$task1->name = 'Task 1';
$task1->type = 'devel';
$task1->mode = 'linear';
$task1->pri = 1;
$task1->estimate = 5;
$task1->consumed = 2;
$task1->left = 3;
$task1->status = 'doing';
$task1->desc = 'Desc 1';
$task1->mailto = '';
$task1->team = '';
$task1->isParent = 0;
$task1->parent = 0;
$task1->openedBy = 'admin';
$task1->openedDate = '2024-01-01 10:00:00';
$task1->assignedTo = 'admin';
$task1->assignedDate = '2024-01-02 11:00:00';
$task1->finishedBy = '';
$task1->finishedDate = '0000-00-00 00:00:00';
$task1->canceledBy = '';
$task1->canceledDate = '0000-00-00 00:00:00';
$task1->closedBy = '';
$task1->closedDate = '0000-00-00 00:00:00';
$task1->closedReason = '';
$task1->lastEditedBy = '';
$task1->lastEditedDate = '0000-00-00 00:00:00';

$baseTask = new stdClass();
$baseTask->type = 'devel';
$baseTask->mode = 'linear';
$baseTask->pri = 1;
$baseTask->status = 'doing';
$baseTask->desc = 'Base desc';
$baseTask->openedBy = 'admin';
$baseTask->openedDate = '2024-01-01 10:00:00';
$baseTask->assignedTo = 'admin';
$baseTask->assignedDate = '2024-01-02 11:00:00';
$baseTask->finishedBy = '';
$baseTask->finishedDate = '0000-00-00 00:00:00';
$baseTask->canceledBy = '';
$baseTask->canceledDate = '0000-00-00 00:00:00';
$baseTask->closedBy = '';
$baseTask->closedDate = '0000-00-00 00:00:00';
$baseTask->closedReason = '';
$baseTask->lastEditedBy = '';
$baseTask->lastEditedDate = '0000-00-00 00:00:00';

$task2 = clone $baseTask;
$task2->id = 2;
$task2->project = 1;
$task2->execution = 1;
$task2->module = 2;
$task2->story = 1;
$task2->fromBug = 0;
$task2->name = 'Task with story';
$task2->estimate = 8;
$task2->consumed = 4;
$task2->left = 4;
$task2->mailto = '';
$task2->team = '';
$task2->isParent = 0;
$task2->parent = 0;

$task3 = clone $baseTask;
$task3->id = 3;
$task3->project = 1;
$task3->execution = 1;
$task3->module = 3;
$task3->story = 0;
$task3->fromBug = 1;
$task3->name = 'Task with bug';
$task3->estimate = 3;
$task3->consumed = 1;
$task3->left = 2;
$task3->mailto = '';
$task3->team = '';
$task3->isParent = 0;
$task3->parent = 0;

$task4 = clone $baseTask;
$task4->id = 4;
$task4->project = 1;
$task4->execution = 1;
$task4->module = 4;
$task4->story = 0;
$task4->fromBug = 0;
$task4->name = 'Multi task';
$task4->estimate = 10;
$task4->consumed = 5;
$task4->left = 5;
$task4->mailto = '';
$task4->team = 'team1';
$task4->isParent = 0;
$task4->parent = 0;

$task5 = clone $baseTask;
$task5->id = 5;
$task5->project = 1;
$task5->execution = 1;
$task5->module = 5;
$task5->story = 0;
$task5->fromBug = 0;
$task5->name = 'Parent task';
$task5->estimate = 20;
$task5->consumed = 10;
$task5->left = 10;
$task5->mailto = '';
$task5->team = '';
$task5->isParent = 1;
$task5->parent = 0;

$task6 = clone $baseTask;
$task6->id = 6;
$task6->project = 1;
$task6->execution = 1;
$task6->module = 6;
$task6->story = 0;
$task6->fromBug = 0;
$task6->name = 'Not started';
$task6->estimate = 5;
$task6->consumed = 0;
$task6->left = 0;
$task6->mailto = '';
$task6->team = '';
$task6->isParent = 0;
$task6->parent = 0;

$task7 = clone $baseTask;
$task7->id = 7;
$task7->project = 1;
$task7->execution = 1;
$task7->module = 7;
$task7->story = 0;
$task7->fromBug = 0;
$task7->name = 'In progress';
$task7->estimate = 10;
$task7->consumed = 3;
$task7->left = 7;
$task7->mailto = '';
$task7->team = '';
$task7->isParent = 0;
$task7->parent = 0;

$task8 = clone $baseTask;
$task8->id = 8;
$task8->project = 1;
$task8->execution = 1;
$task8->module = 8;
$task8->story = 0;
$task8->fromBug = 0;
$task8->name = 'Completed';
$task8->estimate = 6;
$task8->consumed = 6;
$task8->left = 0;
$task8->mailto = '';
$task8->team = '';
$task8->isParent = 0;
$task8->parent = 0;

r($taskTest->processExportDataTest(array($task1), 1)) && p('0:name') && e('Task 1');
r($taskTest->processExportDataTest(array($task2), 1)) && p('0:story') && e('用户需求1(#1)');
r($taskTest->processExportDataTest(array($task3), 1)) && p('0:fromBug') && e('#1 BUG1');
r($taskTest->processExportDataTest(array($task4), 1)) && p('0:name') && e('[多人] Multi task');
r($taskTest->processExportDataTest(array($task5), 1)) && p('0:name') && e('[父] Parent task');
r($taskTest->processExportDataTest(array($task6), 1)) && p('0:progress') && e('0%');
r($taskTest->processExportDataTest(array($task7), 1)) && p('0:progress') && e('30%');
r($taskTest->processExportDataTest(array($task8), 1)) && p('0:progress') && e('100%');