#!/usr/bin/env php
<?php

/**

title=测试 aiModel::hasModelsAvailable();
timeout=0
cid=15053

- 步骤1：有启用且未删除的模型时 @1
- 步骤2：只有禁用但未删除的模型时 @0
- 步骤3：只有启用但已删除的模型时 @0
- 步骤4：禁用且已删除的模型时 @0
- 步骤5：没有任何模型数据时 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_model');
$table->id->range('1-5');
$table->type->range('llm{5}');
$table->vendor->range('openai{2},claude{2},gemini{1}');
$table->credentials->range('{"apiKey":"test_key"}');
$table->name->range('GPT-4{2},Claude-3{2},Gemini{1}');
$table->desc->range('Test model{5}');
$table->createdBy->range('admin{5}');
$table->createdDate->range('`2024-01-01`');
$table->enabled->range('1{3},0{2}');
$table->deleted->range('0{4},1{1}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->hasModelsAvailableTest()) && p() && e('1'); // 步骤1：有启用且未删除的模型时

// 清空数据，插入只有禁用模型的测试数据
global $tester;
$tester->dao->delete()->from(TABLE_AI_MODEL)->exec();
$tester->dao->insert(TABLE_AI_MODEL)->data(array('type' => 'llm', 'vendor' => 'openai', 'credentials' => '{"apiKey":"test"}', 'name' => 'Test', 'desc' => 'Test', 'createdBy' => 'admin', 'createdDate' => '2024-01-01', 'enabled' => '0', 'deleted' => '0'))->exec();
r($aiTest->hasModelsAvailableTest()) && p() && e('0'); // 步骤2：只有禁用但未删除的模型时

// 插入只有启用但已删除的模型
$tester->dao->delete()->from(TABLE_AI_MODEL)->exec();
$tester->dao->insert(TABLE_AI_MODEL)->data(array('type' => 'llm', 'vendor' => 'openai', 'credentials' => '{"apiKey":"test"}', 'name' => 'Test', 'desc' => 'Test', 'createdBy' => 'admin', 'createdDate' => '2024-01-01', 'enabled' => '1', 'deleted' => '1'))->exec();
r($aiTest->hasModelsAvailableTest()) && p() && e('0'); // 步骤3：只有启用但已删除的模型时

// 插入禁用且已删除的模型
$tester->dao->delete()->from(TABLE_AI_MODEL)->exec();
$tester->dao->insert(TABLE_AI_MODEL)->data(array('type' => 'llm', 'vendor' => 'openai', 'credentials' => '{"apiKey":"test"}', 'name' => 'Test', 'desc' => 'Test', 'createdBy' => 'admin', 'createdDate' => '2024-01-01', 'enabled' => '0', 'deleted' => '1'))->exec();
r($aiTest->hasModelsAvailableTest()) && p() && e('0'); // 步骤4：禁用且已删除的模型时

// 清空所有模型数据
$tester->dao->delete()->from(TABLE_AI_MODEL)->exec();
r($aiTest->hasModelsAvailableTest()) && p() && e('0'); // 步骤5：没有任何模型数据时