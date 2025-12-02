#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
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

/**

title=测试executionModel->buildBurnDataTest();
timeout=0
cid=16269

*/

$executionIDList = array(3, 4, 5);
$count           = array('0', '1');
$typeList        = array('noweekend', 'withweekend');
$burnByList      = array('left', 'estimate', 'storyPoint');

$executionTester = new executionTest();
r($executionTester->buildBurnDataTest($executionIDList[0], $count[0], $typeList[0], $burnByList[0])) && p('labels:0')   && e('7/1');  // 按照剩余工时查看敏捷执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[1], $count[0], $typeList[0], $burnByList[0])) && p('burnLine:0') && e('0');    // 按照剩余工时查看瀑布执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[2], $count[0], $typeList[0], $burnByList[0])) && p('baseLine:0') && e('36');   // 按照剩余工时查看看板执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[0], $count[1], $typeList[0], $burnByList[0])) && p()             && e('4');    // 按照剩余工时查看结果统计
r($executionTester->buildBurnDataTest($executionIDList[0], $count[0], $typeList[1], $burnByList[0])) && p('labels:0')   && e('7/1');  // 按照剩余工时查看敏捷执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[1], $count[0], $typeList[1], $burnByList[0])) && p('burnLine:0') && e('0');    // 按照剩余工时查看瀑布执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[2], $count[0], $typeList[1], $burnByList[0])) && p('baseLine:0') && e('36');   // 按照剩余工时查看看板执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[0], $count[1], $typeList[1], $burnByList[0])) && p()             && e('4');    // 按照剩余工时查看结果统计
r($executionTester->buildBurnDataTest($executionIDList[0], $count[0], $typeList[0], $burnByList[1])) && p('labels:0')   && e('7/1');  // 按照计划工时查看敏捷执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[1], $count[0], $typeList[0], $burnByList[1])) && p('burnLine:0') && e('0');    // 按照计划工时查看瀑布执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[2], $count[0], $typeList[0], $burnByList[1])) && p('baseLine:0') && e('94.3'); // 按照计划工时查看看板执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[0], $count[1], $typeList[0], $burnByList[1])) && p()             && e('4');    // 按照计划工时查看结果统计
r($executionTester->buildBurnDataTest($executionIDList[0], $count[0], $typeList[0], $burnByList[2])) && p('labels:0')   && e('7/1');  // 按照故事点查看敏捷执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[1], $count[0], $typeList[0], $burnByList[2])) && p('burnLine:0') && e('0');    // 按照故事点查看瀑布执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[2], $count[0], $typeList[0], $burnByList[2])) && p('baseLine:0') && e('0');    // 按照故事点查看看板执行燃尽图数据
r($executionTester->buildBurnDataTest($executionIDList[0], $count[1], $typeList[0], $burnByList[2])) && p()             && e('4');    // 按照故事点查看结果统计
