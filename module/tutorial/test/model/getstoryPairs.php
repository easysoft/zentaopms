#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStoryPairs();
timeout=0
cid=0

- 步骤1：验证返回数组包含所有需求键值对 @4
- 步骤2：验证Epic需求标题正确属性1 @Test epic
- 步骤3：验证用户需求标题正确属性2 @Test requirement
- 步骤4：验证研发需求标题正确属性3 @Test active story
- 步骤5：验证评审中需求标题正确属性4 @Test reviewing story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$storyPairs = $tutorialTest->getStoryPairsTest();
r(count($storyPairs)) && p() && e('4'); // 步骤1：验证返回数组包含所有需求键值对
r($storyPairs) && p('1') && e('Test epic'); // 步骤2：验证Epic需求标题正确
r($storyPairs) && p('2') && e('Test requirement'); // 步骤3：验证用户需求标题正确
r($storyPairs) && p('3') && e('Test active story'); // 步骤4：验证研发需求标题正确
r($storyPairs) && p('4') && e('Test reviewing story'); // 步骤5：验证评审中需求标题正确