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
$execution->code->range('1-5')->prefix('code');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220110 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220220 000000:0')->type('timestamp')->format('YY/MM/DD');
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

title=测试executionModel->getBurnDataTest();
timeout=0
cid=1

- 敏捷执行查询统计第2022-01-22条的value属性 @36
- 瀑布执行查询统计第2022-01-22条的value属性 @40.2
- 看板执行查询统计第2022-01-22条的value属性 @3

*/

$executionIDList = array(3, 4, 5);

$execution = new executionTest();
r(current($execution->getBurnDataTest($executionIDList[0]))) && p('2022-01-22:value') && e('36');   // 敏捷执行查询统计
r(current($execution->getBurnDataTest($executionIDList[1]))) && p('2022-01-22:value') && e('40.2'); // 瀑布执行查询统计
r(current($execution->getBurnDataTest($executionIDList[2]))) && p('2022-01-22:value') && e('3');    // 看板执行查询统计
