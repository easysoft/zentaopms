#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerEstimate();
timeout=0
cid=18515

- 测试默认story类型的预计工时统计 @6
- 测试返回的工时分组数据结构
 - 第0条的name属性 @2.00
 - 第0条的value属性 @3
- 测试requirement类型的预计工时统计 @6
- 测试空数据集的预计工时统计 @0
- 测试特定工时值的查询 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 清理数据并准备测试数据
$story = zenData('story');
$story->id->range('1-30');
$story->type->range('story{20},requirement{10}');
$story->estimate->range('1{5},2{3},5{4},8{3},0{5},20{10}');
$story->status->range('active,closed{2},draft{1}');
$story->deleted->range('0{25},1{5}');
$story->gen(30);

su('admin');

$storyTest = new storyModelTest();

// 测试步骤1：测试默认story类型的预计工时统计
unset($_SESSION['storyOnlyCondition']);
unset($_SESSION['storyQueryCondition']);
r(count($storyTest->getDataOfStoriesPerEstimateTest('story'))) && p() && e('6'); // 测试默认story类型的预计工时统计

// 测试步骤2：测试返回的工时分组数据结构
$result = $storyTest->getDataOfStoriesPerEstimateTest('story');
r($result) && p('0:name,value') && e('2.00,3'); // 测试返回的工时分组数据结构

// 测试步骤3：测试requirement类型的预计工时统计
r(count($storyTest->getDataOfStoriesPerEstimateTest('requirement'))) && p() && e('6'); // 测试requirement类型的预计工时统计

// 测试步骤4：测试空数据集的预计工时统计
$_SESSION['storyOnlyCondition'] = true;
$_SESSION['storyQueryCondition'] = "id = 999";
r(count($storyTest->getDataOfStoriesPerEstimateTest('story'))) && p() && e('0'); // 测试空数据集的预计工时统计

// 测试步骤5：测试特定工时值的查询
$_SESSION['storyQueryCondition'] = "estimate = 0 AND type = 'story' AND deleted = '0'";
r(count($storyTest->getDataOfStoriesPerEstimateTest('story'))) && p() && e('1'); // 测试特定工时值的查询