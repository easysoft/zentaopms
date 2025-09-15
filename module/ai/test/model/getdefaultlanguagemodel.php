#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getDefaultLanguageModel();
timeout=0
cid=0

- 步骤1：有启用模型时返回ID最小的启用模型属性id @1
- 步骤2：验证返回的是第一个启用模型的名称属性name @GPT-4
- 步骤3：验证返回的模型类型属性type @gpt
- 步骤4：验证返回的模型是启用状态属性enabled @1
- 步骤5：验证返回的模型未被删除属性deleted @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_model');
$table->id->range('1-10');
$table->type->range('gpt{3},claude{2},gemini{2},llama{2},bard{1}');
$table->vendor->range('openai{3},anthropic{2},google{2},meta{2},google{1}');
$table->name->range('GPT-4{1},GPT-3.5{1},Claude-3-Opus{1},Claude-3-Sonnet{1},Gemini-Pro{1},Gemini-Flash{1},LLaMA-2-70B{1},LLaMA-2-13B{1},LLaMA-2-7B{1},Bard{1}');
$table->credentials->range('{"api_key": "test_key_1"},{"api_key": "test_key_2"}{9}');
$table->enabled->range('1{6},0{4}');
$table->deleted->range('0{10}');
$table->createdBy->range('admin{5},user{5}');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`,`2024-01-06 10:00:00`,`2024-01-07 10:00:00`,`2024-01-08 10:00:00`,`2024-01-09 10:00:00`,`2024-01-10 10:00:00`');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r($aiTest->getDefaultLanguageModelTest()) && p('id') && e('1'); // 步骤1：有启用模型时返回ID最小的启用模型
r($aiTest->getDefaultLanguageModelTest()) && p('name') && e('GPT-4'); // 步骤2：验证返回的是第一个启用模型的名称
r($aiTest->getDefaultLanguageModelTest()) && p('type') && e('gpt'); // 步骤3：验证返回的模型类型
r($aiTest->getDefaultLanguageModelTest()) && p('enabled') && e('1'); // 步骤4：验证返回的模型是启用状态
r($aiTest->getDefaultLanguageModelTest()) && p('deleted') && e('0'); // 步骤5：验证返回的模型未被删除