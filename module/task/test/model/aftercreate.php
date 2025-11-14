#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('projectstory')->gen(0); // Clear the table zt_projectstory to make sure the story is not projected.

$task = zenData('task');
$task->id->range('1-4');
$task->type->range('test,devel,design,story');
$task->design->range('0{2},1,0{4}');
$task->name->range('测试,开发,设计,需求')->postfix('任务1');
$task->story->range('0{3},1');
$task->status->range('wait');
$task->gen(4);

$bug = zenData('bug');
$bug->id->range(1);
$bug->product->range('1');
$bug->title->range('Bug1');
$bug->gen(1);

$todo = zenData('todo');
$todo->id->range(1);
$todo->name->range('待办1');
$todo->gen(1);

$design = zenData('design');
$design->id->range(1);
$design->name->range('设计1');
$design->gen(1);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1');
$story->title->range('1-5')->prefix('需求');
$story->type->range('story');
$story->gen(5);

/**

title=测试taskModel->afterCreate();
timeout=0
cid=18761

- 测试空数据 @0
- 测试taskID为空的情况 @0
- 测试taskID不存在的情况 @0
- 测试taskIdList为空的情况属性name @测试任务1
- 测试taskIdList为空，但是有BugID的情况属性title @Bug1
- 测试taskIdList为空，但是有todoID的情况属性name @待办1
- 测试taskIdList为空，但是有testTasks的情况属性name @测试任务1
- 测试Bug转任务后，更新Bug的信息属性toTask @1
- 测试待办转任务后，更新待办的信息属性status @done
- 测试任务关联设计后，更新任务中的designVersion字段属性designVersion @1
- 测试任务关联需求后，更新需求的阶段属性stage @planned
- 测试任务创建子任务后，父任务的parent字段的值属性parent @0

*/

$taskIdList['test']   = array(1);
$taskIdList['todo']   = array(2);
$taskIdList['design'] = array(3);
$taskIdList['story']  = array(4);

$testTasks[2] = new stdclass();
$testTasks[2]->name   = '测试子任务1';
$testTasks[2]->type   = 'test';
$testTasks[2]->mailto = '';

$testTasks[3] = new stdclass();
$testTasks[3]->name   = '测试子任务2';
$testTasks[3]->type   = 'test';
$testTasks[3]->mailto = '';

$taskTester = new taskTest();
r($taskTester->afterCreateTest())                                         && p()                && e('0');         // 测试空数据
r($taskTester->afterCreateTest(0, $taskIdList['test']))                   && p()                && e('0');         // 测试taskID为空的情况
r($taskTester->afterCreateTest(5, $taskIdList['test']))                   && p()                && e('0');         // 测试taskID不存在的情况
r($taskTester->afterCreateTest(1))                                        && p('name')          && e('测试任务1'); // 测试taskIdList为空的情况
r($taskTester->afterCreateTest(1, array(), 1))                            && p('title')         && e('Bug1');      // 测试taskIdList为空，但是有BugID的情况
r($taskTester->afterCreateTest(1, array(), 0, 1))                         && p('name')          && e('待办1');     // 测试taskIdList为空，但是有todoID的情况
r($taskTester->afterCreateTest(1, array(), 0, 0, $testTasks))             && p('name')          && e('测试任务1'); // 测试taskIdList为空，但是有testTasks的情况
r($taskTester->afterCreateTest(1, $taskIdList['test'], 1))                && p('toTask')        && e('1');         // 测试Bug转任务后，更新Bug的信息
r($taskTester->afterCreateTest(2, $taskIdList['todo'], 0, 1))             && p('status')        && e('done');      // 测试待办转任务后，更新待办的信息
r($taskTester->afterCreateTest(3, $taskIdList['design']))                 && p('designVersion') && e('1');         // 测试任务关联设计后，更新任务中的designVersion字段
r($taskTester->afterCreateTest(4, $taskIdList['story']))                  && p('stage')         && e('planned');   // 测试任务关联需求后，更新需求的阶段
r($taskTester->afterCreateTest(1, $taskIdList['test'], 0, 0, $testTasks)) && p('parent')        && e('0');         // 测试任务创建子任务后，父任务的parent字段的值