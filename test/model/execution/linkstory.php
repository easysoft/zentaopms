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

敏捷执行关联需求     >> 3,1,4
瀑布执行关联需求     >> 4,1,4
看板执行关联需求     >> 5,1,4
敏捷执行关联需求统计 >> 3
瀑布执行关联需求统计 >> 3
看板执行关联需求统计 >> 3

*/

$executionIDList = array('3', '4', '5');
$stories         = array('4', '324', '364');
$products        = array('4' => '1', '324' => '2', '364' => '3');
$count           = array('0','1');

$story   = array('stories' => $stories, 'products' => $products);

$execution = new executionTest();
r($execution->linkStoryTest($executionIDList[0], $count[0], $story)) && p('0:project,product,story') && e('3,1,4'); // 敏捷执行关联需求
r($execution->linkStoryTest($executionIDList[1], $count[0], $story)) && p('0:project,product,story') && e('4,1,4'); // 瀑布执行关联需求
r($execution->linkStoryTest($executionIDList[2], $count[0], $story)) && p('0:project,product,story') && e('5,1,4'); // 看板执行关联需求
r($execution->linkStoryTest($executionIDList[0], $count[1], $story)) && p()                          && e('3');       // 敏捷执行关联需求统计
r($execution->linkStoryTest($executionIDList[1], $count[1], $story)) && p()                          && e('3');       // 瀑布执行关联需求统计
r($execution->linkStoryTest($executionIDList[2], $count[1], $story)) && p()                          && e('3');       // 看板执行关联需求统计
