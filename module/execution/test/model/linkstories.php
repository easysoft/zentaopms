#!/usr/bin/env php
<?php

/**

title=测试 executionModel::linkStories();
timeout=0
cid=16353

- 测试步骤1：正常的敏捷执行关联需求 @1
- 测试步骤2：瀑布执行关联需求（无计划） @0
- 测试步骤3：看板执行关联需求 @1
- 测试步骤4：无效执行ID测试 @0
- 测试步骤5：执行ID为0的边界值测试 @0
- 测试步骤6：正常执行关联需求 @1
- 测试步骤7：正常执行关联需求 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$project = zenData('project');
$project->id->range('101-110');
$project->name->range('迭代1,迭代2,看板1,看板2,项目1,项目2,阶段1,阶段2,迭代3,迭代4');
$project->project->range('11,11,12,12,0,0,13,13,14,14');
$project->type->range('sprint{2},kanban{2},project{2},stage{2},sprint{2}');
$project->status->range('doing');
$project->parent->range('11,11,12,12,0,0,13,13,14,14');
$project->grade->range('1{8},2{2}');
$project->path->range(',11,101,,11,102,,12,103,,12,104,,105,,106,,13,107,,13,108,,14,109,,14,110,')->prefix(',')->postfix(',');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal');
$product->gen(5);

$story = zenData('story');
$story->id->range('1-20');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10,需求11,需求12,需求13,需求14,需求15,需求16,需求17,需求18,需求19,需求20');
$story->type->range('story');
$story->status->range('active');
$story->gen(20);

$productplan = zenData('productplan');
$productplan->id->range('1-5');
$productplan->product->range('1-5');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5');
$productplan->gen(5);

$planstory = zenData('planstory');
$planstory->plan->range('1-5');
$planstory->story->range('1-5');
$planstory->gen(5);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('101-105');
$projectproduct->product->range('1-5');
$projectproduct->plan->range('1-5');
$projectproduct->gen(5);

su('admin');

$executionTest = new executionTest();

r($executionTest->linkStoriesTest(101, 1, 1)) && p() && e('1'); // 测试步骤1：正常的敏捷执行关联需求
r($executionTest->linkStoriesTest(102, 0, 0)) && p() && e('0'); // 测试步骤2：瀑布执行关联需求（无计划）
r($executionTest->linkStoriesTest(103, 3, 3)) && p() && e('1'); // 测试步骤3：看板执行关联需求
r($executionTest->linkStoriesTest(999, 1, 1)) && p() && e('0'); // 测试步骤4：无效执行ID测试
r($executionTest->linkStoriesTest(0, 1, 1)) && p() && e('0'); // 测试步骤5：执行ID为0的边界值测试
r($executionTest->linkStoriesTest(104, 4, 4)) && p() && e('1'); // 测试步骤6：正常执行关联需求
r($executionTest->linkStoriesTest(105, 5, 5)) && p() && e('1'); // 测试步骤7：正常执行关联需求