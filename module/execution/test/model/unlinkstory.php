#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

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

$projectStory = zenData('projectstory');
$projectStory->project->range('3-5');
$projectStory->product->range('1-3');
$projectStory->story->range('4,324,364');
$projectStory->gen(3);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$story = zenData('story');
$story->id->range('4,324,364');
$story->title->range('1-3')->prefix('需求');
$story->type->range('story');
$story->status->range('active');
$story->version->range('1');
$story->gen(3);

$cell = zenData('kanbancell');
$cell->id->range('1');
$cell->kanban->range('5');
$cell->gen(1);

$spec = zenData('storyspec');
$spec->story->range('4,324,364');
$spec->version->range('1');
$spec->gen(3);

su('admin');

/**

title=测试executionModel->unlinkStoryTest();
timeout=0
cid=0

- 敏捷执行解除关联需求，移除迭代3中的需求4
 - 第0条的project属性 @3
 - 第0条的product属性 @1
 - 第0条的story属性 @324
- 瀑布执行解除关联需求，移除迭代4中的需求324
 - 第0条的project属性 @4
 - 第0条的product属性 @1
 - 第0条的story属性 @4
- 看板执行解除关联需求，移除迭代5中的需求364
 - 第0条的project属性 @5
 - 第0条的product属性 @1
 - 第0条的story属性 @4
- 敏捷执行关联需求统计 @2
- 瀑布执行关联需求统计 @2
- 看板执行关联需求统计 @2

*/

$executionIDList = array(3, 4, 5);
$stories         = array(4, 324, 364);
$count           = array(0, 1);

$execution = new executionTest();
r($execution->unlinkStoryTest($executionIDList[0], $stories[0], $stories, $count[0])) && p('0:project,product,story') && e('3,1,324'); // 敏捷执行解除关联需求，移除迭代3中的需求4
r($execution->unlinkStoryTest($executionIDList[1], $stories[1], $stories, $count[0])) && p('0:project,product,story') && e('4,1,4');   // 瀑布执行解除关联需求，移除迭代4中的需求324
r($execution->unlinkStoryTest($executionIDList[2], $stories[2], $stories, $count[0])) && p('0:project,product,story') && e('5,1,4');   // 看板执行解除关联需求，移除迭代5中的需求364
r($execution->unlinkStoryTest($executionIDList[0], $stories[0], $stories, $count[1])) && p()                          && e('2');       // 敏捷执行关联需求统计
r($execution->unlinkStoryTest($executionIDList[1], $stories[1], $stories, $count[1])) && p()                          && e('2');       // 瀑布执行关联需求统计
r($execution->unlinkStoryTest($executionIDList[2], $stories[2], $stories, $count[1])) && p()                          && e('2');       // 看板执行关联需求统计
