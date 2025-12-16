#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getStoryPairs();
timeout=0
cid=19479

- 步骤1：验证返回结果是数组格式 @1
- 步骤2：验证数组包含正确数量的需求键值对 @4
- 步骤3：验证Epic类型需求的键值对映射关系属性1 @Test epic
- 步骤4：验证Requirement类型需求的键值对映射关系属性2 @Test requirement
- 步骤5：验证Story类型需求的键值对映射关系属性3 @Test active story

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
r(is_array($storyPairs)) && p() && e('1'); // 步骤1：验证返回结果是数组格式
r(count($storyPairs)) && p() && e('4'); // 步骤2：验证数组包含正确数量的需求键值对
r($tutorialTest->getStoryPairsTest()) && p('1') && e('Test epic'); // 步骤3：验证Epic类型需求的键值对映射关系
r($tutorialTest->getStoryPairsTest()) && p('2') && e('Test requirement'); // 步骤4：验证Requirement类型需求的键值对映射关系
r($tutorialTest->getStoryPairsTest()) && p('3') && e('Test active story'); // 步骤5：验证Story类型需求的键值对映射关系