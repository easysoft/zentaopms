#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-20');
$task->project->range('0');
$task->name->range('1-20')->prefix('任务');
$task->module->range('1-5');
$task->parent->range('0{15},1{5}');
$task->execution->range('1-2');
$task->story->range('1-10');
$task->mode->range('[]{15},multi{3},linear{2}');
$task->storyVersion->range('1');
$task->deadline->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$task->status->range('wait,doing{2},done{2},pause,cancel,closed');
$task->assignedTo->range('admin{10},user1{11-20}');
$task->finishedBy->range('admin{10},user2{11-20}');
$task->pri->range('1-4');
$task->gen(20);

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

su('admin');

/**

title=taskModel->fetchExecutionTasks();
timeout=0
cid=18875

- 测试获取执行ID 0 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 0 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务数量 @10
- 测试获取执行ID 1 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 1 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 1 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 1 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 1 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 1 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 1 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 1 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 1 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 1 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务第11条的name属性 @任务11
- 测试获取执行ID 1 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 1 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 1 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 1 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 1 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 1 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务第17条的name属性 @任务17
- 测试获取执行ID 1 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 1 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 1 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 1 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务第17条的name属性 @任务17
- 测试获取执行ID 1 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 1 product 0 type all module 空 orederBy 'pri_desc' 的任务第1条的name属性 @任务1
- 测试获取执行ID 1 product 0 type all module 空 orederBy 'pri_desc' 的任务数量 @10
- 测试获取执行ID 2 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务第18条的name属性 @任务18
- 测试获取执行ID 2 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务数量 @10
- 测试获取执行ID 2 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 2 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 2 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务第6条的name属性 @任务6
- 测试获取执行ID 2 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务数量 @1
- 测试获取执行ID 2 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务第2条的name属性 @任务2
- 测试获取执行ID 2 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务 @0
- 测试获取执行ID 2 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务数量 @0
- 测试获取执行ID 2 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务第2条的name属性 @任务2
- 测试获取执行ID 2 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务数量 @2
- 测试获取执行ID 2 product 0 type all module 空 orederBy 'pri_desc' 的任务第20条的name属性 @任务20
- 测试获取执行ID 2 product 0 type all module 空 orederBy 'pri_desc' 的任务数量 @10

*/

$executionIdList = array(0, 1, 2);
$productIdList   = array(0, 1, 8);
$type            = array('all', 'assignedbyme', 'myinvolved', 'undone', 'needconfirm', 'assignedtome', 'finishedbyme', 'delayed', 'wait', 'doing', 'done', 'pause', 'cancel', array('wait', 'doing', 'done', 'pause', 'cancel'));
$modules         = array(array(), array(2), array(8), array(2,8));
$orderBy         = array('status_asc, id_desc', 'pri_desc');
$count           = array(0, 1);

$task = new taskTest();

r($task->fetchExecutionTasksTest($executionIdList[0], $productIdList[0], $type[0], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 0 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[0], $productIdList[0], $type[0], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 0 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[0], $orderBy[0], $count[1]))  && p()          && e('10');     // 测试获取执行ID 1 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[0], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[0], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 1 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[1], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 1 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[1], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 1 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[2], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[2], $modules[0], $orderBy[0], $count[1]))  && p()          && e('1');      // 测试获取执行ID 1 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[3], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[3], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 1 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[4], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 1 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[4], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 1 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[5], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[5], $modules[0], $orderBy[0], $count[1]))  && p()          && e('1');      // 测试获取执行ID 1 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[6], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[6], $modules[0], $orderBy[0], $count[1]))  && p()          && e('1');      // 测试获取执行ID 1 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[7], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[7], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 1 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[8], $modules[0], $orderBy[0], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[8], $modules[0], $orderBy[0], $count[1]))  && p()          && e('1');      // 测试获取执行ID 1 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[9], $modules[0], $orderBy[0], $count[0]))  && p('11:name') && e('任务11'); // 测试获取执行ID 1 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[9], $modules[0], $orderBy[0], $count[1]))  && p()          && e('1');      // 测试获取执行ID 1 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[10], $modules[0], $orderBy[0], $count[0])) && p()          && e('0');      // 测试获取执行ID 1 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[10], $modules[0], $orderBy[0], $count[1])) && p()          && e('0');      // 测试获取执行ID 1 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[11], $modules[0], $orderBy[0], $count[0])) && p()          && e('0');      // 测试获取执行ID 1 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[11], $modules[0], $orderBy[0], $count[1])) && p()          && e('0');      // 测试获取执行ID 1 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[12], $modules[0], $orderBy[0], $count[0])) && p()          && e('0');      // 测试获取执行ID 1 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[12], $modules[0], $orderBy[0], $count[1])) && p()          && e('0');      // 测试获取执行ID 1 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[13], $modules[0], $orderBy[0], $count[0])) && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[1], $type[13], $modules[0], $orderBy[0], $count[1])) && p()          && e('2');      // 测试获取执行ID 1 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[1], $orderBy[0], $count[0]))  && p('17:name') && e('任务17'); // 测试获取执行ID 1 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[1], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 1 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[2], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 1 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[2], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 1 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[3], $orderBy[0], $count[0]))  && p('17:name') && e('任务17'); // 测试获取执行ID 1 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[3], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 1 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[0], $orderBy[1], $count[0]))  && p('1:name')  && e('任务1');  // 测试获取执行ID 1 product 0 type all module 空 orederBy 'pri_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[1], $productIdList[0], $type[0], $modules[0], $orderBy[1], $count[1]))  && p()          && e('10');     // 测试获取执行ID 1 product 0 type all module 空 orederBy 'pri_desc' 的任务数量

r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[0], $orderBy[0], $count[0]))  && p('18:name') && e('任务18'); // 测试获取执行ID 2 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[0], $orderBy[0], $count[1]))  && p()          && e('10');     // 测试获取执行ID 2 product 0 type all module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[0], $modules[0], $orderBy[0], $count[0]))  && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[0], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 1 type all module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[1], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[1], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type assignedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[2], $modules[0], $orderBy[0], $count[0]))  && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[2], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 1 type myinvolved module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[3], $modules[0], $orderBy[0], $count[0]))  && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[3], $modules[0], $orderBy[0], $count[1]))  && p()          && e('1');      // 测试获取执行ID 2 product 1 type undone module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[4], $modules[0], $orderBy[0], $count[0]))  && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[4], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 1 type needconfirm module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[5], $modules[0], $orderBy[0], $count[0]))  && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[5], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 1 type assignedtome module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[6], $modules[0], $orderBy[0], $count[0]))  && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[6], $modules[0], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 1 type finishedbyme module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[7], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[7], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type delayed module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[8], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[8], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type wait module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[9], $modules[0], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[9], $modules[0], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 2 product 1 type doing module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[10], $modules[0], $orderBy[0], $count[0])) && p()          && e('0');      // 测试获取执行ID 2 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[10], $modules[0], $orderBy[0], $count[1])) && p()          && e('0');      // 测试获取执行ID 2 product 1 type done module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[11], $modules[0], $orderBy[0], $count[0])) && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[11], $modules[0], $orderBy[0], $count[1])) && p()          && e('1');      // 测试获取执行ID 2 product 1 type pause module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[12], $modules[0], $orderBy[0], $count[0])) && p()          && e('0');      // 测试获取执行ID 2 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[12], $modules[0], $orderBy[0], $count[1])) && p()          && e('0');      // 测试获取执行ID 2 product 1 type cancel module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[13], $modules[0], $orderBy[0], $count[0])) && p('6:name')  && e('任务6');  // 测试获取执行ID 2 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[1], $type[13], $modules[0], $orderBy[0], $count[1])) && p()          && e('1');      // 测试获取执行ID 2 product 1 type array('wait', 'doing', 'done', 'pause', 'cancel') module 空 orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[1], $orderBy[0], $count[0]))  && p('2:name')  && e('任务2');  // 测试获取执行ID 2 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[1], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 0 type all module array(2) orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[2], $orderBy[0], $count[0]))  && p()          && e('0');      // 测试获取执行ID 2 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[2], $orderBy[0], $count[1]))  && p()          && e('0');      // 测试获取执行ID 2 product 0 type all module array(8) orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[3], $orderBy[0], $count[0]))  && p('2:name')  && e('任务2');  // 测试获取执行ID 2 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[3], $orderBy[0], $count[1]))  && p()          && e('2');      // 测试获取执行ID 2 product 0 type all module array(2,8) orederBy 'status_asc, id_desc' 的任务数量
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[0], $orderBy[1], $count[0]))  && p('20:name') && e('任务20'); // 测试获取执行ID 2 product 0 type all module 空 orederBy 'pri_desc' 的任务
r($task->fetchExecutionTasksTest($executionIdList[2], $productIdList[0], $type[0], $modules[0], $orderBy[1], $count[1]))  && p()          && e('10');     // 测试获取执行ID 2 product 0 type all module 空 orederBy 'pri_desc' 的任务数量