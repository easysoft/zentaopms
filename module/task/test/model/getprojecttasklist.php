#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-20');
$task->name->range('1-20')->prefix('任务');
$task->module->range('1-5');
$task->parent->range('0{15},0{5}');
$task->execution->range('3-5');
$task->project->range('1-2');
$task->story->range('1-10');
$task->mode->range('[]{15},multi{3},linear{2}');
$task->storyVersion->range('1');
$task->deadline->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$task->status->range('wait,doing{2},done{2},pause,cancel,closed');
$task->assignedTo->range('admin{10},user1{11-20}');
$task->finishedBy->range('admin{10},user2{11-20}');
$task->pri->range('1-4');
$task->gen(20);

su('admin');

/**

title=taskModel->getProjectTaskList();
timeout=0
cid=18818

- 测试获取项目ID 0下的任务 @0
- 测试获取项目ID 1下的任务第1条的name属性 @任务1
- 测试获取项目ID 2下的任务第2条的name属性 @任务2
- 测试获取项目ID 1 isPairs true下的任务属性1 @任务1
- 测试获取项目ID 2 isPairs true下的任务属性2 @任务2
- 测试获取项目ID 1 isPairs true下的任务属性1 @任务1
- 测试获取项目ID 2 isPairs true下的任务属性2 @任务2 [跨执行]
- 测试获取项目ID 3下的任务 @0

*/

$task = $tester->loadModel('task');

r($task->getProjectTaskList(0))          && p()         && e('0');               // 测试获取项目ID 0下的任务
r($task->getProjectTaskList(1))          && p('1:name') && e('任务1');           // 测试获取项目ID 1下的任务
r($task->getProjectTaskList(2))          && p('2:name') && e('任务2');           // 测试获取项目ID 2下的任务
r($task->getProjectTaskList(1, true))    && p('1')      && e('任务1');           // 测试获取项目ID 1 isPairs true下的任务
r($task->getProjectTaskList(2, true))    && p('2')      && e('任务2');           // 测试获取项目ID 2 isPairs true下的任务
r($task->getProjectTaskList(1, true, 3)) && p('1')      && e('任务1');           // 测试获取项目ID 1 isPairs true下的任务
r($task->getProjectTaskList(2, true, 5)) && p('2')      && e('任务2 [跨执行]');  // 测试获取项目ID 2 isPairs true下的任务
r($task->getProjectTaskList(3))          && p()         && e('0');               // 测试获取项目ID 3下的任务
