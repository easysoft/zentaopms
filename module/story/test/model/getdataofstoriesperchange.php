#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerChange();
timeout=0
cid=18513

- 测试正常情况下按需求变更次数分组统计 @4
- 测试变更次数为0的需求(版本号为1)
 - 第0条的name属性 @2
 - 第0条的value属性 @3
- 测试不同storyType参数的影响
 - 第3条的name属性 @0
 - 第3条的value属性 @10
- 测试空数据情况下的处理 @2
- 测试数据排序的正确性第0条的value属性 @3
- 测试边界值版本号的处理第3条的value属性 @10
- 测试会话条件过滤的影响 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 准备测试数据：创建不同版本的需求
$story = zenData('story');
$story->id->range('1-25');
$story->type->range('story{20},requirement{5}'); // 20个story，5个requirement
$story->version->range('1{8},2{4},3{3},4{8},1{2}'); // 版本1：10个，版本2：4个，版本3：3个，版本4：8个
$story->deleted->range('0');
$story->gen(25);

su('admin');

$storyTest = new storyTest();

// 设置会话条件
$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` <= 25";

// 测试步骤1：正常情况下按需求变更次数分组统计
r(count($storyTest->getDataOfStoriesPerChangeTest())) && p() && e('4'); // 测试正常情况下按需求变更次数分组统计

// 测试步骤2：验证按value排序，第一个分组的数据（最小value）
r($storyTest->getDataOfStoriesPerChangeTest()) && p('0:name,value') && e('2,3'); // 测试变更次数为0的需求(版本号为1)

// 测试步骤3：验证版本1对应变更次数0存在于结果中（第4个位置）
r($storyTest->getDataOfStoriesPerChangeTest()) && p('3:name,value') && e('0,10'); // 测试不同storyType参数的影响

// 测试步骤4：测试会话条件对数据的影响
$_SESSION['storyQueryCondition'] = "`id` <= 10";
r(count($storyTest->getDataOfStoriesPerChangeTest())) && p() && e('2'); // 测试空数据情况下的处理

// 测试步骤5：验证数据按value排序（第一个值应该是最小的）
$_SESSION['storyQueryCondition'] = "`id` <= 25";
r($storyTest->getDataOfStoriesPerChangeTest()) && p('0:value') && e('3'); // 测试数据排序的正确性

// 测试步骤6：验证最后一个值是最大的（确保排序正确）
r($storyTest->getDataOfStoriesPerChangeTest()) && p('3:value') && e('10'); // 测试边界值版本号的处理

// 测试步骤7：测试没有设置requirement会话条件时返回所有数据
r(count($storyTest->getDataOfStoriesPerChangeTest('requirement'))) && p() && e('4'); // 测试会话条件过滤的影响