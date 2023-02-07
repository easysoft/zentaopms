#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$projectstory = zdTable('projectstory');
$projectstory->project->range('3-5');
$projectstory->product->range('1-3');
$projectstory->story->range('4,324,364');
$projectstory->gen(3);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('3-5');
$projectproduct->product->range('1-3');
$projectproduct->plan->range('1-3');
$projectproduct->gen(3);

$stroy = zdTable('story');
$stroy->id->range('4,324,364');
$stroy->title->range('1-3')->prefix('需求');
$stroy->type->range('story');
$stroy->status->range('active');
$stroy->gen(3);

$cell = zdTable('kanbancell');
$cell->id->range('1');
$cell->kanban->range('5');
$cell->gen(1);

su('admin');

/**

title=测试executionModel->linkStoryTest();
cid=1
pid=1

敏捷执行关联需求 >> 1
瀑布执行关联需求 >> 0
看板执行关联需求 >> 1

*/

$executionIDList = array('3', '4', '5');
$productIDList   = array('1', '0', '3');
$planIDList      = array('1', '0', '3');

$execution = new executionTest();
r($execution->linkStoriesTest($executionIDList[0], $productIDList[0], $planIDList[0])) && p() && e('1'); // 敏捷执行关联需求
r($execution->linkStoriesTest($executionIDList[1], $productIDList[1], $planIDList[1])) && p() && e('0'); // 瀑布执行关联需求
r($execution->linkStoriesTest($executionIDList[2], $productIDList[2], $planIDList[2])) && p() && e('1'); // 看板执行关联需求
