#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

$task = zdTable('task');
$task->id->range('1');
$task->type->range('test');
$task->name->range('测试任务1');
$task->status->range('wait');
$task->gen(1);

$story = zdTable('story');
$story->id->range('1-5');
$story->product->range('1');
$story->title->range('1-5')->prefix('需求');
$story->type->range('story');
$story->gen(5);

zdTable('action')->gen(0);

/**

title=测试taskModel->createTestChildTasks();
timeout=0
cid=1

*/

$testTasks[2] = new stdclass();
$testTasks[2]->name     = '测试子任务1';
$testTasks[2]->type     = 'test';
$testTasks[2]->estimate = 0;
$testTasks[2]->left     = 0;
$testTasks[2]->story    = 1;
$testTasks[2]->mailto   = '';

$testTasks[3] = new stdclass();
$testTasks[3]->name     = '测试子任务2';
$testTasks[3]->type     = 'test';
$testTasks[3]->estimate = 0;
$testTasks[3]->left     = 0;
$testTasks[2]->left     = 1;
$testTasks[3]->mailto   = '';

$taskTester = new taskTest();
r($taskTester->createTestChildTasksTest())              && p()   && e('0');                         // 测试空数据
r($taskTester->createTestChildTasksTest(0, $testTasks)) && p('type') && e('test');                  // 测试任务ID为空的情况
r($taskTester->createTestChildTasksTest(1, array()))    && p('id')   && e('2');                     // 测试子测试任务的数据为空的情况
r($taskTester->createTestChildTasksTest(1, $testTasks)) && p('name') && e('测试研发需求 #2 需求2'); // 测试创建任务ID为1的子测试任务
