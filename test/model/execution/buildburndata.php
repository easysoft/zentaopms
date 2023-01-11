#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$burn = zdTable('burn');
$burn->execution->range('3{5},4{5},5{5}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('94.3,56.3,55.3,37.8,33.8');
$burn->left->range('95.3,68.5,73.9,40.2,36,3');
$burn->consumed->range('20.1,33.4,41,56.55,59.55');
$burn->storyPoint->range('0,16.5,16,11.5,9');
$burn->gen(15);

/**

title=测试executionModel->buildBurnDataTest();
cid=1
pid=1

敏捷执行燃尽图数据 >> 7/1
瀑布执行燃尽图数据 >> [0,0,0,0,0,0,0,0,0,3,95.3]
看板执行燃尽图数据 >> [36,32.4,28.8,25.2,21.6,18,14.4,10.8,7.2,3.6,0]
结果统计 >> 4

*/

$executionIDList = array(3, 4, 5);
$count           = array('0', '1');

$executionTester = new executionTest();
r($executionTester->buildBurnDataTest($executionIDList[0], $count[0])) && p('labels:0') && e('7/1');                                             // 敏捷执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[1], $count[0])) && p('burnLine') && e('[0,0,0,0,0,0,0,0,0,3,95.3]');                      // 瀑布执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[2], $count[0])) && p('baseLine') && e('[36,32.4,28.8,25.2,21.6,18,14.4,10.8,7.2,3.6,0]'); // 看板执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[0], $count[1])) && p()           && e('4');                                               // 结果统计
