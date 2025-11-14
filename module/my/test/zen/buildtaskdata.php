#!/usr/bin/env php
<?php

/**

title=测试 myZen::buildTaskData();
timeout=0
cid=17315

- 测试空任务数组返回结果 @0
- 测试独立任务工时标签添加第1条的estimateLabel属性 @5h
- 测试任务状态为doing时保持原状态第1条的status属性 @doing
- 测试子任务存在父任务时父任务hasChild标记第2条的hasChild属性 @1
- 测试子任务存在父任务时子任务parent不变第3条的parent属性 @2
- 测试子任务不存在父任务时名称拼接第4条的name属性 @开发任务10 / 子任务A
- 测试子任务不存在父任务时parent变为0第4条的parent属性 @0
- 测试需求状态变更时任务状态改为changed第5条的status属性 @changed
- 测试任务状态为cancel时不变更为changed第6条的status属性 @cancel
- 测试任务状态为closed时不变更为changed第7条的status属性 @closed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$taskTable = zenData('task');
$taskTable->id->range('1-20');
$taskTable->name->range('开发任务1,开发任务2,开发任务3,开发任务4,开发任务5,开发任务6,开发任务7,开发任务8,开发任务9,开发任务10,开发任务11,开发任务12,开发任务13,开发任务14,开发任务15,开发任务16,开发任务17,开发任务18,开发任务19,开发任务20');
$taskTable->parent->range('0');
$taskTable->estimate->range('1-10');
$taskTable->consumed->range('0-5');
$taskTable->left->range('0-8');
$taskTable->status->range('wait');
$taskTable->gen(20);

zenData('user')->gen(10);

su('admin');

$myTest = new myZenTest();

$emptyTasks = array();

$task1 = new stdClass();
$task1->id = 1;
$task1->name = '独立任务1';
$task1->parent = 0;
$task1->estimate = 5;
$task1->consumed = 2;
$task1->left = 3;
$task1->status = 'doing';
$task1->storyStatus = '';
$task1->storyVersion = 1;
$task1->latestStoryVersion = 1;

$task2 = new stdClass();
$task2->id = 2;
$task2->name = '父任务A';
$task2->parent = 0;
$task2->estimate = 10;
$task2->consumed = 3;
$task2->left = 7;
$task2->status = 'doing';
$task2->storyStatus = '';
$task2->storyVersion = 1;
$task2->latestStoryVersion = 1;

$task3 = new stdClass();
$task3->id = 3;
$task3->name = '子任务B';
$task3->parent = 2;
$task3->estimate = 3;
$task3->consumed = 1;
$task3->left = 2;
$task3->status = 'wait';
$task3->storyStatus = '';
$task3->storyVersion = 1;
$task3->latestStoryVersion = 1;

$task4 = new stdClass();
$task4->id = 4;
$task4->name = '子任务A';
$task4->parent = 10;
$task4->estimate = 4;
$task4->consumed = 2;
$task4->left = 2;
$task4->status = 'doing';
$task4->storyStatus = '';
$task4->storyVersion = 1;
$task4->latestStoryVersion = 1;

$task5 = new stdClass();
$task5->id = 5;
$task5->name = '需求变更任务';
$task5->parent = 0;
$task5->estimate = 8;
$task5->consumed = 4;
$task5->left = 4;
$task5->status = 'doing';
$task5->storyStatus = 'active';
$task5->storyVersion = 1;
$task5->latestStoryVersion = 2;

$task6 = new stdClass();
$task6->id = 6;
$task6->name = '已取消任务';
$task6->parent = 0;
$task6->estimate = 6;
$task6->consumed = 3;
$task6->left = 0;
$task6->status = 'cancel';
$task6->storyStatus = 'active';
$task6->storyVersion = 1;
$task6->latestStoryVersion = 2;

$task7 = new stdClass();
$task7->id = 7;
$task7->name = '已关闭任务';
$task7->parent = 0;
$task7->estimate = 7;
$task7->consumed = 7;
$task7->left = 0;
$task7->status = 'closed';
$task7->storyStatus = 'active';
$task7->storyVersion = 1;
$task7->latestStoryVersion = 2;

$singleTask = array(1 => $task1);
$tasksWithParent = array(1 => $task1, 2 => $task2, 3 => $task3);
$taskWithoutParent = array(4 => $task4);
$taskChanged = array(5 => $task5);
$taskCanceled = array(6 => $task6);
$taskClosed = array(7 => $task7);

r($myTest->buildTaskDataTest($emptyTasks)) && p('') && e('0'); // 测试空任务数组返回结果
r($myTest->buildTaskDataTest($singleTask)) && p('1:estimateLabel') && e('5h'); // 测试独立任务工时标签添加
r($myTest->buildTaskDataTest($singleTask)) && p('1:status') && e('doing'); // 测试任务状态为doing时保持原状态
r($myTest->buildTaskDataTest($tasksWithParent)) && p('2:hasChild') && e('1'); // 测试子任务存在父任务时父任务hasChild标记
r($myTest->buildTaskDataTest($tasksWithParent)) && p('3:parent') && e('2'); // 测试子任务存在父任务时子任务parent不变
r($myTest->buildTaskDataTest($taskWithoutParent)) && p('4:name') && e('开发任务10 / 子任务A'); // 测试子任务不存在父任务时名称拼接
r($myTest->buildTaskDataTest($taskWithoutParent)) && p('4:parent') && e('0'); // 测试子任务不存在父任务时parent变为0
r($myTest->buildTaskDataTest($taskChanged)) && p('5:status') && e('changed'); // 测试需求状态变更时任务状态改为changed
r($myTest->buildTaskDataTest($taskCanceled)) && p('6:status') && e('cancel'); // 测试任务状态为cancel时不变更为changed
r($myTest->buildTaskDataTest($taskClosed)) && p('7:status') && e('closed'); // 测试任务状态为closed时不变更为changed