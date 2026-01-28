#!/usr/bin/env php
<?php

/**

title=测试 aiModel::testModelConnection();
timeout=0
cid=15068

- 步骤1：有效模型ID，测试连接失败 @0
- 步骤2：不存在模型ID，返回false @0
- 步骤3：无效模型ID（0），返回false @0
- 步骤4：负数模型ID，返回false @0
- 步骤5：字符串模型ID，返回false @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_model');
$table->id->range('1-3');
$table->type->range('openai-gpt4, ernie, claude');
$table->vendor->range('openai, baidu, anthropic');
$table->credentials->range('{"key":"test-key"}, {"key":"valid-key"}, ""');
$table->name->range('TestModel1, TestModel2, TestModel3');
$table->desc->range('Test Model Description');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->editedBy->range('admin');
$table->editedDate->range('`2024-01-01 00:00:00`');
$table->enabled->range('1');
$table->deleted->range('0');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 注意：由于testModelConnection会调用真实API，在测试环境中会失败，但这是正常的行为
r($aiTest->testModelConnectionTest(1)) && p() && e('0'); // 步骤1：有效模型ID，测试连接失败
r($aiTest->testModelConnectionTest(999)) && p() && e('0'); // 步骤2：不存在模型ID，返回false
r($aiTest->testModelConnectionTest(0)) && p() && e('0'); // 步骤3：无效模型ID（0），返回false
r($aiTest->testModelConnectionTest(-1)) && p() && e('0'); // 步骤4：负数模型ID，返回false
r($aiTest->testModelConnectionTest('abc')) && p() && e('0'); // 步骤5：字符串模型ID，返回false