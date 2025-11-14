#!/usr/bin/env php
<?php

/**

title=测试 aiModel::setModelConfig();
timeout=0
cid=15066

- 步骤1：正常配置对象 @1
- 步骤2：数据库模型对象 @1
- 步骤3：空配置对象 @1
- 步骤4：null配置 @0
- 步骤5：false配置 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（简化处理，不依赖数据库数据）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 准备测试数据
$normalConfig = new stdclass();
$normalConfig->type = 'chat';
$normalConfig->vendor = 'openai';
$normalConfig->apiKey = 'test-api-key';
$normalConfig->baseUrl = 'https://api.openai.com';

$dbModelConfig = new stdclass();
$dbModelConfig->id = 1;
$dbModelConfig->type = 'chat';
$dbModelConfig->vendor = 'openai';
$dbModelConfig->credentials = '{"apiKey":"test-key-123","baseUrl":"https://api.test.com"}';
$dbModelConfig->proxy = '{"host":"proxy.test.com","port":8080}';
$dbModelConfig->name = 'GPT-4';
$dbModelConfig->desc = '强大的对话模型';
$dbModelConfig->enabled = '1';
$dbModelConfig->deleted = '0';

$emptyConfig = new stdclass();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->setModelConfigTest($normalConfig)) && p() && e('1'); // 步骤1：正常配置对象
r($aiTest->setModelConfigTest($dbModelConfig)) && p() && e('1'); // 步骤2：数据库模型对象
r($aiTest->setModelConfigTest($emptyConfig)) && p() && e('1'); // 步骤3：空配置对象
r($aiTest->setModelConfigTest(null)) && p() && e('0'); // 步骤4：null配置
r($aiTest->setModelConfigTest(false)) && p() && e('0'); // 步骤5：false配置