#!/usr/bin/env php
<?php

/**

title=测试 aiModel::updateModel();
timeout=0
cid=15077

- 步骤1：正常更新情况 @1
- 步骤2：不存在的模型ID @0
- 步骤3：缺少必需凭证 @0
- 步骤4：更新名称成功 @1
- 步骤5：Azure模型完整更新 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$aiModelTable = zenData('ai_model');
$aiModelTable->id->range('1-5');
$aiModelTable->type->range('openai-gpt35{3},openai-gpt4{1},openai-gpt35{1}');
$aiModelTable->vendor->range('openai{3},openai{1},azure{1}');
$aiModelTable->credentials->range('{"key":"test-key-1"},{"key":"test-key-2"},{"key":"test-key-3"},{"key":"test-key-4"},{"key":"test-key-azure","resource":"test-resource","deployment":"test-deployment"}');
$aiModelTable->name->range('Test Model 1,Test Model 2,Test Model 3,Test Model 4,Test Azure Model');
$aiModelTable->desc->range('Description 1,Description 2,Description 3,Description 4,Azure Description');
$aiModelTable->enabled->range('1{4},0{1}');
$aiModelTable->deleted->range('0');
$aiModelTable->createdBy->range('admin');
$aiModelTable->createdDate->range('`2024-01-01 10:00:00`');
$aiModelTable->gen(5);

// 暂时不准备im_chat表数据，专注测试模型更新逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常更新OpenAI模型
$validUpdateModel = new stdclass();
$validUpdateModel->name = 'Updated OpenAI Model';
$validUpdateModel->description = 'Updated OpenAI GPT-3.5 model description';
$validUpdateModel->type = 'openai-gpt35';
$validUpdateModel->vendor = 'openai';
$validUpdateModel->key = 'sk-updated-api-key-12345';
r($aiTest->updateModelTest(1, $validUpdateModel)) && p() && e('1'); // 步骤1：正常更新情况

// 测试步骤2：尝试更新不存在的模型ID
r($aiTest->updateModelTest(999, $validUpdateModel)) && p() && e('0'); // 步骤2：不存在的模型ID

// 测试步骤3：更新模型但缺少必需的凭证
$invalidCredentialsModel = new stdclass();
$invalidCredentialsModel->name = 'Invalid Model';
$invalidCredentialsModel->description = 'Model without required credentials';
$invalidCredentialsModel->type = 'openai-gpt35';
$invalidCredentialsModel->vendor = 'openai';
// 缺少key字段
r($aiTest->updateModelTest(2, $invalidCredentialsModel)) && p() && e('0'); // 步骤3：缺少必需凭证

// 测试步骤4：更新模型名称
$nameUpdateModel = new stdclass();
$nameUpdateModel->name = 'Updated Model Name';
$nameUpdateModel->description = 'Model with updated name test';
$nameUpdateModel->type = 'openai-gpt35';
$nameUpdateModel->vendor = 'openai';
$nameUpdateModel->key = 'sk-name-update-test-key';
r($aiTest->updateModelTest(3, $nameUpdateModel)) && p() && e('1'); // 步骤4：更新名称成功

// 测试步骤5：更新Azure模型的完整信息
$azureUpdateModel = new stdclass();
$azureUpdateModel->name = 'Updated Azure Model';
$azureUpdateModel->description = 'Updated Azure OpenAI model with full config';
$azureUpdateModel->type = 'openai-gpt35';
$azureUpdateModel->vendor = 'azure';
$azureUpdateModel->key = 'updated-azure-api-key';
$azureUpdateModel->resource = 'updated-azure-resource';
$azureUpdateModel->deployment = 'updated-deployment-name';
r($aiTest->updateModelTest(5, $azureUpdateModel)) && p() && e('1'); // 步骤5：Azure模型完整更新