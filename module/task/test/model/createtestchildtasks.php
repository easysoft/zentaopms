#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

$task = zenData('task');
$task->id->range('1,2');
$task->type->range('test');
$task->name->range('测试任务1');
$task->status->range('wait');
$task->gen(2);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1');
$story->title->range('1-5')->prefix('需求');
$story->type->range('story');
$story->gen(5);

zenData('action')->gen(0);

/**

title=测试taskModel->createTestChildTasks();
timeout=0
cid=18785

- 测试空数据 @0
- 测试任务ID为空的情况属性type @test
- 测试子测试任务的数据为空的情况属性id @3
- 测试创建任务ID为1的子测试任务属性name @测试研发需求 #2 需求2
- 测试创建任务ID为1的子测试任务属性name @测试 #1 需求1

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
r($taskTester->createTestChildTasksTest(1, array()))    && p('id')   && e('3');                     // 测试子测试任务的数据为空的情况
r($taskTester->createTestChildTasksTest(1, $testTasks)) && p('name') && e('测试研发需求 #2 需求2'); // 测试创建任务ID为1的子测试任务
r($taskTester->createTestChildTasksTest(2, $testTasks)) && p('name') && e('测试 #1 需求1');         // 测试创建任务ID为2的子测试任务
