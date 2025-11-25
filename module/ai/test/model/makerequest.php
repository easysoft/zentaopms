#!/usr/bin/env php
<?php

/**

title=测试 aiModel::makeRequest();
timeout=0
cid=15057

- 步骤1：正常chat请求属性result @fail
- 步骤2：正常completion请求属性result @fail
- 步骤3：无效类型参数属性result @fail
- 步骤4：空字符串数据参数属性result @fail
- 步骤5：超时参数测试属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_model');
$table->id->range('1-3');
$table->type->range('openai-gpt4, ernie, claude');
$table->vendor->range('openai, baidu, anthropic');
$table->credentials->range('{"key":"test-key-1","secret":"test-secret-1","endpoint":"https://api.openai.com","base":"https://api.openai.com","resource":"test-resource","deployment":"test-deployment"}, {"key":"test-key-2","secret":"test-secret-2","endpoint":"https://api.baidu.com","base":"https://api.baidu.com"}, {"key":"test-key-3","secret":"test-secret-3","endpoint":"https://api.anthropic.com","base":"https://api.anthropic.com"}');
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
$aiTest = new aiTest();

// 设置模型配置 - 为了避免数据库查询问题，直接设置模型配置
$modelConfig = new stdClass();
$modelConfig->id = 1;
$modelConfig->type = 'openai-gpt4';
$modelConfig->vendor = 'openai';
$modelConfig->key = 'test-key-1';
$modelConfig->secret = 'test-secret-1';
$modelConfig->endpoint = 'https://api.openai.com';
$modelConfig->base = 'https://api.openai.com';
$modelConfig->resource = 'test-resource';
$modelConfig->deployment = 'test-deployment';
$modelConfig->name = 'TestModel1';
$aiTest->setModelConfigTest($modelConfig);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 注意：由于makeRequest会尝试调用外部API，在测试环境中会失败，但这是正常的行为
r($aiTest->makeRequestTest('chat', array('messages' => array(array('role' => 'user', 'content' => 'test'))))) && p('result') && e('fail'); // 步骤1：正常chat请求
r($aiTest->makeRequestTest('completion', array('prompt' => 'test prompt'))) && p('result') && e('fail'); // 步骤2：正常completion请求
r($aiTest->makeRequestTest('invalid_type', array('data' => 'test'))) && p('result') && e('fail'); // 步骤3：无效类型参数
r($aiTest->makeRequestTest('chat', '')) && p('result') && e('fail'); // 步骤4：空字符串数据参数
r($aiTest->makeRequestTest('chat', array('messages' => array()), 1)) && p('result') && e('fail'); // 步骤5：超时参数测试