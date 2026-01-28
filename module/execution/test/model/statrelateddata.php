#!/usr/bin/env php
<?php

/**

title=测试 executionModel::statRelatedData();
timeout=0
cid=16368

- 执行executionTest模块的statRelatedDataTest方法，参数是3
 - 属性storyCount @3
 - 属性taskCount @3
 - 属性bugCount @4
- 执行executionTest模块的statRelatedDataTest方法，参数是4
 - 属性storyCount @1
 - 属性taskCount @3
 - 属性bugCount @1
- 执行executionTest模块的statRelatedDataTest方法，参数是5
 - 属性storyCount @0
 - 属性taskCount @0
 - 属性bugCount @0
- 执行executionTest模块的statRelatedDataTest方法，参数是999
 - 属性storyCount @0
 - 属性taskCount @0
 - 属性bugCount @0
- 执行executionTest模块的statRelatedDataTest方法
 - 属性storyCount @0
 - 属性taskCount @0
 - 属性bugCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目1,项目2,有数据执行,仅任务执行,空执行,不存在执行');
$execution->type->range('project{2},sprint{2},waterfall{2}');
$execution->status->range('doing{4},closed{2}');
$execution->parent->range('0,0,1,1,2,2');
$execution->grade->range('2{2},1{4}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`2,6`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->type->range('story');
$story->status->range('active{3},draft{2}');
$story->deleted->range('0{4},1{1}');
$story->gen(5);

$projectstory = zenData('projectstory');
$projectstory->project->range('3{3},4{1}');
$projectstory->product->range('1{4}');
$projectstory->story->range('1,2,3,4');
$projectstory->gen(4);

$task = zenData('task');
$task->id->range('1-8');
$task->execution->range('3{3},4{3},6{2}');
$task->status->range('wait{3},doing{3},done{2}');
$task->deleted->range('0{7},1{1}');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(8);

$bug = zenData('bug');
$bug->id->range('1-6');
$bug->project->range('1{2},2{2},3{2}');
$bug->execution->range('3{4},4{1},5{1}');
$bug->status->range('active{3},resolved{2},closed{1}');
$bug->deleted->range('0{5},1{1}');
$bug->gen(6);

su('admin');

$executionTest = new executionModelTest();

r($executionTest->statRelatedDataTest(3))   && p('storyCount,taskCount,bugCount') && e('3,3,4');
r($executionTest->statRelatedDataTest(4))   && p('storyCount,taskCount,bugCount') && e('1,3,1');
r($executionTest->statRelatedDataTest(5))   && p('storyCount,taskCount,bugCount') && e('0,0,0');
r($executionTest->statRelatedDataTest(999)) && p('storyCount,taskCount,bugCount') && e('0,0,0');
r($executionTest->statRelatedDataTest(0))   && p('storyCount,taskCount,bugCount') && e('0,0,0');
