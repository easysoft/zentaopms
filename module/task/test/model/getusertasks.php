#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('userview')->gen(0);

$task = zenData('task');
$task->id->range('1-20');
$task->name->range('1-20')->prefix('任务');
$task->module->range('1-5');
$task->parent->range('0{15},1{5}');
$task->execution->range('3-5');
$task->project->range('1');
$task->story->range('1-10');
$task->mode->range('[]{15},multi{3},linear{2}');
$task->storyVersion->range('1');
$task->deadline->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$task->status->range('wait,doing{2},done{2},pause,cancel,closed');
$task->assignedTo->range('admin,user1');
$task->finishedBy->range('[]{3},user1{5}');
$task->closedBy->range('[]{7},user1{1}');
$task->pri->range('1-4');
$task->gen(20);

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('1');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->gen(5);

$story = zenData('story');
$story->id->range('1-20');
$story->title->range('1-20')->prefix('需求');
$story->product->range('1-20');
$story->branch->range('0');
$story->version->range('1-2');
$story->status->range('active{10},draft{5},reviewing{2},closed{2},changing');
$story->gen(20);

zenData('user')->gen(30);

$taskTeam = zenData('taskteam');
$taskTeam->id->range('1-5');
$taskTeam->task->range('16{2},19{3}');
$taskTeam->account->range('admin,user1,admin,user1,user2');
$taskTeam->estimate->range('1{2},2{3}');
$taskTeam->left->range('1{2},1{3}');
$taskTeam->status->range('wait{2},doing{3}');
$taskTeam->gen(5);

$module = zenData('module');
$module->root->range('1-5');
$module->type->range('story');
$module->gen(5);

/**

title=taskModel->getUserTasks();
timeout=0
cid=18828

- 查看指派给用户1的任务第20条的name属性 @任务20
- 检查指派给用户1的任务数量 @10
- 查看由用户1关闭的任务第16条的name属性 @任务16
- 检查由用户1关闭的任务数量 @2
- 查看由用户1完成的任务第20条的name属性 @任务20
- 检查由用户1完成的任务数量 @11
- 查找8条指派给用户1的任务 @8
- 查看项目1下由用户1完成的任务第20条的name属性 @任务20
- 查看项目1下由用户1完成的任务数量 @11
- 查看不存在的项目下由用户1完成的任务 @0
- 查看不存在的项目下由用户1完成的任务数量 @0

*/
su('admin');
$task = new taskModelTest();

r($task->getUserTasksTest('user1', 'assignedTo'))                               && p('20:name') && e('任务20'); // 查看指派给用户1的任务
r(count($task->getUserTasksTest('user1', 'assignedTo')))                        && p()          && e('10');     // 检查指派给用户1的任务数量
r($task->getUserTasksTest('user1', 'closedBy'))                                 && p('16:name') && e('任务16'); // 查看由用户1关闭的任务
r(count($task->getUserTasksTest('user1', 'closedBy')))                          && p()          && e('2');      // 检查由用户1关闭的任务数量
r($task->getUserTasksTest('user1', 'finishedBy'))                               && p('20:name') && e('任务20'); // 查看由用户1完成的任务
r(count($task->getUserTasksTest('user1', 'finishedBy')))                        && p()          && e('11');     // 检查由用户1完成的任务数量
r(count($task->getUserTasksTest('user1', 'assignedTo', 8)))                     && p()          && e('8');      // 查找8条指派给用户1的任务
r($task->getUserTasksTest('user1', 'finishedBy', 0, null, 'id_desc', 1))        && p('20:name') && e('任务20'); // 查看项目1下由用户1完成的任务
r(count($task->getUserTasksTest('user1', 'finishedBy', 0, null, 'id_desc', 1))) && p()          && e('11');     // 查看项目1下由用户1完成的任务数量
r($task->getUserTasksTest('user1', 'finishedBy', 0, null, 'id_desc', 2))        && p()          && e('0');      // 查看不存在的项目下由用户1完成的任务
r(count($task->getUserTasksTest('user1', 'finishedBy', 0, null, 'id_desc', 2))) && p()          && e('0');      // 查看不存在的项目下由用户1完成的任务数量
