#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

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
$product->name->range('正常产品1,多分支产品1,多平台产品1');
$product->type->range('normal,branch,platform');
$product->status->range('closed{2},normal');
$product->createdBy->range('admin,user1');
$product->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-10');
$projectproduct->product->range('1-3');
$projectproduct->plan->range('1-3');
$projectproduct->gen(10);

zenData('user')->gen(5);
su('admin');

/**

title=测试executionModel->getRelatedExecutionsTest();
timeout=0
cid=16336

*/

$executionIDList = array('5','7', '9');
$count           = array('0','1');

$execution = new executionTest();
r($execution->getRelatedExecutionsTest($executionIDList[0],$count[0])) && p('2')  && e('项目1');  // 查询敏捷执行关联查询
r($execution->getRelatedExecutionsTest($executionIDList[1],$count[0])) && p('4')  && e('项目3');  // 查询瀑布执行关联查询
r($execution->getRelatedExecutionsTest($executionIDList[2],$count[0])) && p('3')  && e('项目2');  // 查询看板执行关联查询
r($execution->getRelatedExecutionsTest($executionIDList[0],$count[1])) && p()      && e('2');     // 查询敏捷执行关联统计
r($execution->getRelatedExecutionsTest($executionIDList[1],$count[1])) && p()      && e('3');     // 查询瀑布执行关联统计
r($execution->getRelatedExecutionsTest($executionIDList[2],$count[1])) && p()      && e('2');     // 查询看板执行关联统计
