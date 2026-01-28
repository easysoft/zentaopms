#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->project->range('0,0,1,1,2');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->storyType->range('story');
$execution->gen(5);

$projectstory = zenData('projectstory');
$projectstory->project->range('3-5');
$projectstory->product->range('1-3');
$projectstory->story->range('4,324,364');
$projectstory->gen(3);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$stroy = zenData('story');
$stroy->id->range('4,324,364');
$stroy->title->range('1-3')->prefix('需求');
$stroy->type->range('story');
$stroy->status->range('active');
$stroy->gen(3);

$stroyspec = zenData('storyspec');
$stroyspec->story->range('4,324,364');
$stroyspec->title->range('1-3')->prefix('需求');
$stroyspec->spec->range('1-3')->prefix('spec');
$stroyspec->version->range('1-3');
$stroyspec->gen(3);

$cell = zenData('kanbancell');
$cell->id->range('1');
$cell->kanban->range('5');
$cell->gen(1);

su('admin');

/**

title=测试executionModel->linkStoryTest();
timeout=0
cid=16354

- 敏捷执行关联需求
 - 第0条的project属性 @3
 - 第0条的product属性 @1
 - 第0条的story属性 @364
- 瀑布执行关联需求
 - 第0条的project属性 @4
 - 第0条的product属性 @1
 - 第0条的story属性 @364
- 看板执行关联需求
 - 第0条的project属性 @5
 - 第0条的product属性 @1
 - 第0条的story属性 @364
- 敏捷执行关联需求统计 @1
- 瀑布执行关联需求统计 @1
- 看板执行关联需求统计 @1

*/

$executionIDList = array(3, 4, 5);
$stories         = array(4, 324, 364);
$count           = array(0, 1);

$execution = new executionModelTest();
r($execution->linkStoryTest($executionIDList[0], $count[0], $stories)) && p('0:project,product,story') && e('3,1,364'); // 敏捷执行关联需求
r($execution->linkStoryTest($executionIDList[1], $count[0], $stories)) && p('0:project,product,story') && e('4,1,364'); // 瀑布执行关联需求
r($execution->linkStoryTest($executionIDList[2], $count[0], $stories)) && p('0:project,product,story') && e('5,1,364'); // 看板执行关联需求
r($execution->linkStoryTest($executionIDList[0], $count[1], $stories)) && p()                          && e('1');       // 敏捷执行关联需求统计
r($execution->linkStoryTest($executionIDList[1], $count[1], $stories)) && p()                          && e('1');       // 瀑布执行关联需求统计
r($execution->linkStoryTest($executionIDList[2], $count[1], $stories)) && p()                          && e('1');       // 看板执行关联需求统计