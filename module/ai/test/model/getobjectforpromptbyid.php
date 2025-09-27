#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getObjectForPromptById();
timeout=0
cid=0

- 步骤1：story模块正常情况，返回数组包含两个元素 @2
- 步骤2：task模块正常情况，返回数组包含两个元素 @2
- 步骤3：不存在的prompt ID @0
- 步骤4：不存在的object ID @0
- 步骤5：deleted状态的prompt @0
- 步骤6：product模块测试，返回数组包含两个元素 @2
- 步骤7：bug模块但object不存在 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（简化，避免数据库依赖）
// 注意：此测试使用模拟数据，不依赖实际数据库

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($aiTest->getObjectForPromptByIdTest(1, 1)) && p() && e('2'); // 步骤1：story模块正常情况，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(3, 2)) && p() && e('2'); // 步骤2：task模块正常情况，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(99, 1)) && p() && e('0'); // 步骤3：不存在的prompt ID
r($aiTest->getObjectForPromptByIdTest(1, 999)) && p() && e('0'); // 步骤4：不存在的object ID
r($aiTest->getObjectForPromptByIdTest(10, 1)) && p() && e('0'); // 步骤5：deleted状态的prompt
r($aiTest->getObjectForPromptByIdTest(7, 1)) && p() && e('2'); // 步骤6：product模块测试，返回数组包含两个元素
r($aiTest->getObjectForPromptByIdTest(5, 999)) && p() && e('0'); // 步骤7：bug模块但object不存在