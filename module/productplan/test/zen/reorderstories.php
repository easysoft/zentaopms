#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::reorderStories();
timeout=0
cid=0

- 步骤1：正常的父子关系需求 @1,2,3,4,5

- 步骤2：LIMIT语句处理 @limit_removed
- 步骤3：空列表处理 @0
- 步骤4：只有父需求 @1,2,3

- 步骤5：只有子需求 @4,5,6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$productplanZenTest = new productplanZenTest();

// 测试步骤1：有父子关系的需求重新排序
$sql1 = 'SELECT * FROM zt_story WHERE product = 1';
$stories1 = array(1 => 0, 2 => 1, 3 => 1, 4 => 0, 5 => 4);
$result1 = $productplanZenTest->reorderStoriesTest($sql1, $stories1);
r(implode(',', $result1['storyBrowseList'])) && p() && e('1,2,3,4,5'); // 步骤1：正常的父子关系需求

// 测试步骤2：带有LIMIT的SQL语句处理
$sql2 = 'SELECT * FROM zt_story WHERE product = 1 LIMIT 10';
$stories2 = array(10 => 0, 11 => 10, 12 => 10);
$result2 = $productplanZenTest->reorderStoriesTest($sql2, $stories2);
r($result2['sqlProcessed']) && p() && e('limit_removed'); // 步骤2：LIMIT语句处理

// 测试步骤3：空需求列表处理
$sql3 = 'SELECT * FROM zt_story WHERE product = 999';
$stories3 = array();
$result3 = $productplanZenTest->reorderStoriesTest($sql3, $stories3);
r($result3['sessionSet']) && p() && e('0'); // 步骤3：空列表处理

// 测试步骤4：只有父需求的列表
$sql4 = 'SELECT * FROM zt_story WHERE parent = 0';
$stories4 = array(1 => 0, 2 => 0, 3 => 0);
$result4 = $productplanZenTest->reorderStoriesTest($sql4, $stories4);
r(implode(',', $result4['epicBrowseList'])) && p() && e('1,2,3'); // 步骤4：只有父需求

// 测试步骤5：只有子需求的列表
$sql5 = 'SELECT * FROM zt_story WHERE parent > 0';
$stories5 = array(4 => 1, 5 => 1, 6 => 2);
$result5 = $productplanZenTest->reorderStoriesTest($sql5, $stories5);
r(implode(',', $result5['requirementBrowseList'])) && p() && e('4,5,6'); // 步骤5：只有子需求