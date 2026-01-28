#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试executionModel->getInvolvedExecutionListTest();
timeout=0
cid=16317

- 根据敏捷项目查询执行列表
 - 第5条的project属性 @2
 - 第5条的name属性 @迭代1
- 根据瀑布项目查询执行列表
 - 第7条的project属性 @3
 - 第7条的name属性 @阶段1
- 根据看板项目查询执行列表
 - 第9条的project属性 @4
 - 第9条的name属性 @看板1
- 根据产品查询执行列表
 - 第5条的project属性 @2
 - 第5条的name属性 @迭代1
 - 第5条的type属性 @sprint
- 根据敏捷项目查询执行数量 @1
- 根据瀑布项目查询执行数量 @1
- 根据看板项目查询执行数量 @1
- 根据产品查询执行数量 @1

*/

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
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

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('user1');
$product->RD->range('user2');
$product->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('2{3}, 3{3}, 4{3}, 5, 6, 7, 8, 9, 10');
$projectProduct->product->range('1-3');
$projectProduct->branch->range('0');
$projectProduct->gen(14);

$projectIDList = array(0, 2, 3,4);
$productIDList = array(0, 1);
$limit         = array(0, 2, 10);
$count         = array(0, 1);

$executionTester = new executionModelTest();
r($executionTester->getInvolvedExecutionListTest($projectIDList[1], $limit[0], $productIDList[0], $count[0])) && p('5:project,name')      && e('2,迭代1');        // 根据敏捷项目查询执行列表
r($executionTester->getInvolvedExecutionListTest($projectIDList[2], $limit[0], $productIDList[0], $count[0])) && p('7:project,name')      && e('3,阶段1');        // 根据瀑布项目查询执行列表
r($executionTester->getInvolvedExecutionListTest($projectIDList[3], $limit[0], $productIDList[0], $count[0])) && p('9:project,name')      && e('4,看板1');        // 根据看板项目查询执行列表
r($executionTester->getInvolvedExecutionListTest($projectIDList[0], $limit[0], $productIDList[1], $count[0])) && p('5:project,name,type') && e('2,迭代1,sprint'); // 根据产品查询执行列表
r($executionTester->getInvolvedExecutionListTest($projectIDList[1], $limit[1], $productIDList[0], $count[1])) && p()                      && e('1');              // 根据敏捷项目查询执行数量
r($executionTester->getInvolvedExecutionListTest($projectIDList[2], $limit[1], $productIDList[0], $count[1])) && p()                      && e('1');              // 根据瀑布项目查询执行数量
r($executionTester->getInvolvedExecutionListTest($projectIDList[3], $limit[1], $productIDList[0], $count[1])) && p()                      && e('1');              // 根据看板项目查询执行数量
r($executionTester->getInvolvedExecutionListTest($projectIDList[0], $limit[2], $productIDList[1], $count[1])) && p()                      && e('1');              // 根据产品查询执行数量
