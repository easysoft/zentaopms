#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLanguageModelNamesWithDefault();
timeout=0
cid=15034

- 步骤1：检查default键存在且值正确属性default @默认
- 步骤2：检查模型1名称属性1 @GPT-4
- 步骤3：检查模型2名称属性2 @GPT-3.5
- 步骤4：检查模型3名称属性3 @Claude-3-Opus
- 步骤5：检查模型4名称属性4 @Claude-3-Sonnet

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_model');
$table->id->range('1-8');
$table->type->range('llm{5},embedding{2},completion{1}');
$table->vendor->range('openai{3},claude{2},gemini{2},local{1}');
$table->credentials->range('api_key_1,api_key_2,api_key_3,api_key_4,api_key_5,api_key_6,api_key_7,api_key_8');
$table->name->range('GPT-4,GPT-3.5,Claude-3-Opus,Claude-3-Sonnet,Gemini-Pro,Local-LLM,Azure-OpenAI,Test-Model');
$table->desc->range('OpenAI GPT-4,OpenAI GPT-3.5,Claude-3-Opus,Claude-3-Sonnet,Google Gemini,Local LLM,Azure OpenAI,Test Model');
$table->createdBy->range('admin{5},user{2},tester{1}');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`,`2024-01-06 10:00:00`,`2024-01-07 10:00:00`,`2024-01-08 10:00:00`');
$table->enabled->range('1{6},0{2}');
$table->deleted->range('0{7},1{1}');
$table->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->getLanguageModelNamesWithDefaultTest()) && p('default') && e('默认'); // 步骤1：检查default键存在且值正确
r($aiTest->getLanguageModelNamesWithDefaultTest()) && p('1') && e('GPT-4'); // 步骤2：检查模型1名称
r($aiTest->getLanguageModelNamesWithDefaultTest()) && p('2') && e('GPT-3.5'); // 步骤3：检查模型2名称
r($aiTest->getLanguageModelNamesWithDefaultTest()) && p('3') && e('Claude-3-Opus'); // 步骤4：检查模型3名称
r($aiTest->getLanguageModelNamesWithDefaultTest()) && p('4') && e('Claude-3-Sonnet'); // 步骤5：检查模型4名称