#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试executionModel->unlinkCasesTest();
timeout=0
cid=16372

- 敏捷执行解除关联用例 @0
- 瀑布执行解除关联用例 @0
- 看板执行解除关联用例 @0
- 敏捷执行解除关联用例 @0
- 敏捷执行解除关联用例 @0

*/

$execution = zenData('project');
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

$projectstory = zenData('projectcase');
$projectstory->project->range('3-5');
$projectstory->product->range('1,43,68');
$projectstory->case->range('4,324,364');
$projectstory->gen(3);

$product = zenData('product');
$product->id->range('1,43,68');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$stroy = zenData('story');
$stroy->id->range('2,170,270');
$stroy->title->range('1-3')->prefix('需求');
$stroy->type->range('story');
$stroy->status->range('active');
$stroy->gen(3);

su('admin');

$executionIDList = array('3', '4', '5');
$products        = array('1', '43', '68');
$stories         = array('2', '170', '270');
$count           = array(0, 1);

$execution = new executionModelTest();
r($execution->unlinkCasesTest($executionIDList[0], $products[0], $stories[0])) && p() && e('0'); // 敏捷执行解除关联用例
r($execution->unlinkCasesTest($executionIDList[1], $products[1], $stories[1])) && p() && e('0'); // 瀑布执行解除关联用例
r($execution->unlinkCasesTest($executionIDList[2], $products[2], $stories[2])) && p() && e('0'); // 看板执行解除关联用例
r($execution->unlinkCasesTest($executionIDList[0], $products[0], $stories[1])) && p() && e('0'); // 敏捷执行解除关联用例
r($execution->unlinkCasesTest($executionIDList[0], $products[1], $stories[0])) && p() && e('0'); // 敏捷执行解除关联用例
