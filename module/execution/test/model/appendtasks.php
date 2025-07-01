#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('1-5')->prefix('执行');
$execution->type->range('sprint,stage,kanban');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5)->fixPath();

$task = zenData('task');
$task->execution->range('1-3');
$task->project->range('0');
$task->story->range('1-10');
$task->storyVersion->range('1{6},2{4}');
$task->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->status->range('active');
$story->version->range('1{6},2{2},3{2}');
$story->gen(10);

su('admin');

/**

title=测试 executionModel->appendTasks();
timeout=0
cid=1

- 判断执行数量 @4
- 判断第一个执行的名称
 - 第1条的execution属性 @1
 - 第1条的status属性 @wait
- 查看获取到的第三个执行的开始日期和结束日期
 - 第3条的rawID属性 @1
 - 第3条的progress属性 @100
- 查看获取非需求变更任务的操作按钮
 - 第0条的name属性 @startTask
 - 第0条的disabled属性 @1
- 查看获取需求变更任务的操作按钮
 - 第0条的name属性 @confirmStoryChange
 - 第0条的disabled属性 @~~

*/

global $app;
$app->user->admin = true;

$executionTester = new executionTest();
$executions      = $executionTester->appendTasksTest();

r(count($executions))      && p()                     && e('4');                     // 判断执行数量
r($executions)             && p('1:execution,status') && e('1,wait');                // 判断第一个执行的名称
r($executions)             && p('3:rawID,progress')   && e('1,100');                 // 查看获取到的第三个执行的开始日期和结束日期
r($executions[2]->actions) && p('0:name,disabled')    && e('startTask,1');           // 查看获取非需求变更任务的操作按钮
r($executions[0]->actions) && p('0:name,disabled')    && e('confirmStoryChange,~~'); // 查看获取需求变更任务的操作按钮
