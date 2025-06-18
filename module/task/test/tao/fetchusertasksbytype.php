#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

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

title=taskModel->fetchUserTasksByType();
timeout=0
cid=1

*/
