#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-100');
$task->name->range('1-100')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing,done');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->finishedBy->range(' ,user1,user2');
$task->gen(100);

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->title->range('1-100')->prefix('BUG');
$bug->execution->range('3-5');
$bug->product->range('1');
$bug->status->range('active');
$bug->gen(100);

/**

title=测试 projectModel->getStatData();
timeout=0
cid=17849

- 统计id=1的项目下数据数量
 - 属性bugCount @0
 - 属性taskCount @67
 - 属性waitCount @34
 - 属性doingCount @33

- 统计id=2的项目下数据数量
 - 属性bugCount @0
 - 属性taskCount @33
 - 属性waitCount @0
 - 属性doingCount @0

- 统计id=3的执行下数据数量
 - 属性bugCount @0
 - 属性taskCount @0

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getStatData(1)) && p('bugCount,taskCount,waitCount,doingCount') && e('0,67,34,33'); //统计id=1的项目下数据数量
r($tester->project->getStatData(2)) && p('bugCount,taskCount,waitCount,doingCount') && e('0,33,0,0'); //统计id=2的项目下数据数量
r($tester->project->getStatData(3)) && p('bugCount,taskCount') && e('0,0'); //统计id=3的执行下数据数量
