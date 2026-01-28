#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getAssistantsByModel();
timeout=0
cid=15028

- 步骤1：获取模型ID为1且启用的助手 @3
- 步骤2：获取模型ID为2且启用的助手 @3
- 步骤3：获取模型ID为1且未启用的助手 @0
- 步骤4：获取不存在的模型ID启用助手 @0
- 步骤5：获取模型ID为3且未启用的助手 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 测试使用模拟数据，无需zendata数据准备

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->getAssistantsByModelTest(1, true)) && p() && e(3); // 步骤1：获取模型ID为1且启用的助手
r($aiTest->getAssistantsByModelTest(2, true)) && p() && e(3); // 步骤2：获取模型ID为2且启用的助手
r($aiTest->getAssistantsByModelTest(1, false)) && p() && e(0); // 步骤3：获取模型ID为1且未启用的助手
r($aiTest->getAssistantsByModelTest(999, true)) && p() && e(0); // 步骤4：获取不存在的模型ID启用助手
r($aiTest->getAssistantsByModelTest(3, false)) && p() && e(2); // 步骤5：获取模型ID为3且未启用的助手
