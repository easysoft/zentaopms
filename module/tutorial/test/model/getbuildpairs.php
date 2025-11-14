#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBuildPairs();
timeout=0
cid=19408

- 步骤1：正常情况，验证ID为1的版本名称属性1 @Test build
- 步骤2：验证指定键的值属性1 @Test build
- 步骤3：验证数组的第一个键 @1
- 步骤4：验证数组的第一个值 @Test build
- 步骤5：验证数组长度 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getBuildPairsTest()) && p('1') && e('Test build'); // 步骤1：正常情况，验证ID为1的版本名称
r($tutorialTest->getBuildPairsTest()) && p('1') && e('Test build'); // 步骤2：验证指定键的值
r(array_keys($tutorialTest->getBuildPairsTest())) && p('0') && e(1); // 步骤3：验证数组的第一个键
r(array_values($tutorialTest->getBuildPairsTest())) && p('0') && e('Test build'); // 步骤4：验证数组的第一个值
r(count($tutorialTest->getBuildPairsTest())) && p() && e(1); // 步骤5：验证数组长度