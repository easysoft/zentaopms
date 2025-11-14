#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

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

$case = zenData('case');
$case->story->range('2,170,270');
$case->gen(9);

su('admin');

/**

title=测试executionModel->linkCasesTest();
timeout=0
cid=16352

- 敏捷执行关联用例
 - 第0条的project属性 @3
 - 第0条的product属性 @1
 - 第0条的case属性 @1
- 瀑布执行关联用例
 - 第0条的project属性 @4
 - 第0条的product属性 @43
 - 第0条的case属性 @2
- 看板执行关联用例
 - 第0条的project属性 @5
 - 第0条的product属性 @68
 - 第0条的case属性 @3
- 敏捷执行关联用例统计 @3
- 瀑布执行关联用例统计 @3
- 看板执行关联用例统计 @3

*/

$executionIdList = array(3, 4, 5);
$productIdList   = array(1, 43, 68);
$storyIdList     = array(2, 170, 270);
$count           = array(0, 1);

$execution = new executionTest();
r($execution->linkCasesTest($executionIdList[0], $productIdList[0], $storyIdList[0], $count[0])) && p('0:project,product,case') && e('3,1,1');  // 敏捷执行关联用例
r($execution->linkCasesTest($executionIdList[1], $productIdList[1], $storyIdList[1], $count[0])) && p('0:project,product,case') && e('4,43,2'); // 瀑布执行关联用例
r($execution->linkCasesTest($executionIdList[2], $productIdList[2], $storyIdList[2], $count[0])) && p('0:project,product,case') && e('5,68,3'); // 看板执行关联用例
r($execution->linkCasesTest($executionIdList[0], $productIdList[0], $storyIdList[0], $count[1])) && p()                         && e('3');      // 敏捷执行关联用例统计
r($execution->linkCasesTest($executionIdList[1], $productIdList[1], $storyIdList[1], $count[1])) && p()                         && e('3');      // 瀑布执行关联用例统计
r($execution->linkCasesTest($executionIdList[2], $productIdList[2], $storyIdList[2], $count[1])) && p()                         && e('3');      // 看板执行关联用例统计
