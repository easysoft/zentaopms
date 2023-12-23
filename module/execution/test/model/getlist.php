#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('user')->gen(5);
zdTable('group')->gen(0);
zdTable('userview')->gen(0);

$execution = zdTable('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,迭代2,阶段1,阶段2,看板1,看板2');
$execution->type->range('program,project{3},sprint{2},stage{2},kanban{2}');
$execution->model->range('[],scrum,waterfall,kanban,[]{6}');
$execution->parent->range('0,1{3},2{2},3{2},4{2}');
$execution->project->range('0{4},2{2},3{2},4{2}');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('user1');
$product->RD->range('user2');
$product->gen(3);

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('2{3}, 3{3}, 4{3}, 5, 6, 7, 8, 9, 10');
$projectProduct->product->range('1-3');
$projectProduct->branch->range('0');
$projectProduct->gen(9);

su('admin');

/**

title=测试executionModel->getListTest();
timeout=0
cid=1

*/

$projectIDList = array(0, 2, 3, 4);
$productIDList = array('0', '1');
$status        = array('all', 'wait', 'doing');
$type          = array('all', 'sprint', 'stage', 'kanban');
$limit         = array('0', '2', '10');
$count         = array('0', '1');

$executionTester = new executionTest();
r($executionTester->getListTest($projectIDList[1], $type[0], $status[0], $limit[0], $productIDList[0], $count[0])) && p('5:project,name')      && e('2,迭代1');        // 敏捷项目执行列表查询
r($executionTester->getListTest($projectIDList[2], $type[0], $status[0], $limit[0], $productIDList[0], $count[0])) && p('7:project,name,type') && e('3,阶段1,stage');  // 瀑布项目执行列表查询
r($executionTester->getListTest($projectIDList[3], $type[0], $status[0], $limit[0], $productIDList[0], $count[0])) && p('9:project,name,type') && e('4,看板1,kanban'); // 看板项目执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[0], $status[0], $limit[0], $productIDList[1], $count[0])) && p()                      && e('0');              // 产品1执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[1], $status[0], $limit[2], $productIDList[0], $count[0])) && p('6:type,name')         && e('sprint,迭代2');   // 敏捷执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[2], $status[0], $limit[2], $productIDList[0], $count[0])) && p('8:type,name')         && e('stage,阶段2');    // 瀑布执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[3], $status[0], $limit[2], $productIDList[0], $count[0])) && p('10:type,name')        && e('kanban,看板2');   // 看板执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[0], $status[1], $limit[0], $productIDList[0], $count[0])) && p()                      && e('0');              // wait执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[0], $status[2], $limit[0], $productIDList[0], $count[0])) && p('5:status,name')       && e('doing,迭代1');    // doing执行列表查询
r($executionTester->getListTest($projectIDList[0], $type[0], $status[1], $limit[1], $productIDList[0], $count[1])) && p()                      && e('0');              // 执行列表2条查询
r($executionTester->getListTest($projectIDList[0], $type[0], $status[2], $limit[2], $productIDList[0], $count[1])) && p()                      && e('6');              // 执行列表10条查询
