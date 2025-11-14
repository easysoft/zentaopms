#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->code->range('1-5')->prefix('code');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$burn = zenData('burn');
$burn->execution->range('3{5},4{5},5{5}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('94.3,56.3,55.3,37.8,33.8');
$burn->left->range('95.3,68.5,73.9,40.2,36,3');
$burn->consumed->range('20.1,33.4,41,56.55,59.55');
$burn->storyPoint->range('0,16.5,16,11.5,9');
$burn->gen(15);

$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('3');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

/**

title=测试 executionModel->fetchBurnData();
timeout=0
cid=16386

*/

global $tester;
$executionModel = $tester->loadModel('execution');

r(current($tester->execution->fetchBurnData(array())))        && p('0:execution,estimate') && e('0,0');  // 测试空数据
r(current($tester->execution->fetchBurnData(array(2, 3, 4)))) && p('3:execution,estimate') && e('3,55'); // 测试获取燃尽图数据
r(current($tester->execution->fetchBurnData(array(7, 8, 9)))) && p('0:execution,estimate') && e('0, 0'); // 测试获取不存在的执行的燃尽图数据
