#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('正常产品1,多分支产品1,多平台产品1');
$product->type->range('normal,branch,platform');
$product->status->range('closed{2},normal');
$product->createdBy->range('admin,user1');
$product->gen(3);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('5-10');
$projectproduct->product->range('1-3');
$projectproduct->plan->range('1-3');
$projectproduct->gen(5);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('2');
$branch->name->range('分支1,分支2');
$branch->status->range('active');
$branch->gen(2);

zenData('team')->gen(0);

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

/**

title=测试executionModel->getStageLinkProductPairsTest();
cid=16339
pid=1

敏捷执行产品查询 >> 正常产品1
瀑布执行产品查询 >> 多平台产品1
看板执行产品查询 >> 多分支产品1
敏捷执行产品统计 >> 2
瀑布执行产品统计 >> 2
看板执行产品统计 >> 1

*/

$sprintIDList = array('5', '6');
$stageIDList  = array('7', '8');
$kanbanIDList = array('9', '10');
$count        = array('0','1');

$execution = new executionTest();
r($execution->getStageLinkProductPairsTest($sprintIDList, $count[0])) && p('5')  && e('正常产品1');   // 敏捷执行产品查询
r($execution->getStageLinkProductPairsTest($stageIDList, $count[0]))  && p('7')  && e('多平台产品1'); // 瀑布执行产品查询
r($execution->getStageLinkProductPairsTest($kanbanIDList, $count[0])) && p('9')  && e('多分支产品1'); // 看板执行产品查询
r($execution->getStageLinkProductPairsTest($sprintIDList, $count[1])) && p()     && e('2');           // 敏捷执行产品统计
r($execution->getStageLinkProductPairsTest($stageIDList, $count[1]))  && p()     && e('2');           // 瀑布执行产品统计
r($execution->getStageLinkProductPairsTest($kanbanIDList, $count[1])) && p()     && e('1');           // 看板执行产品统计
