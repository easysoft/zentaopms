#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getPrintKanbanData();
timeout=0
cid=16433

- 步骤1：正常执行ID和故事数据返回数组 @Array
- 步骤2：空故事数组情况返回数组 @(
- 步骤3：无效执行ID情况返回数组 @[0] => Array
- 步骤4：执行ID为0的边界情况返回数组 @(
- 步骤5：不同执行ID情况返回数组 @)

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备 - 移除数据生成，用实际数据测试

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$executionZenTest = new executionZenTest();

// 5. 测试步骤
$emptyStories = array();
$stories = array('story1', 'story2');

r($executionZenTest->getPrintKanbanDataTest(1, $stories)) && p() && e('Array'); // 步骤1：正常执行ID和故事数据返回数组
r($executionZenTest->getPrintKanbanDataTest(1, $emptyStories)) && p() && e('('); // 步骤2：空故事数组情况返回数组
r($executionZenTest->getPrintKanbanDataTest(999, $stories)) && p() && e('[0] => Array'); // 步骤3：无效执行ID情况返回数组
r($executionZenTest->getPrintKanbanDataTest(0, $stories)) && p() && e('('); // 步骤4：执行ID为0的边界情况返回数组
r($executionZenTest->getPrintKanbanDataTest(2, $stories)) && p() && e(')'); // 步骤5：不同执行ID情况返回数组