#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getDefaultLanguageModel();
timeout=0
cid=15030

- 执行aiTest模块的getDefaultLanguageModelTest方法 属性id @1
- 执行aiTest模块的getDefaultLanguageModelTest方法 属性id @5
- 执行aiTest模块的getDefaultLanguageModelTest方法  @0
- 执行aiTest模块的getDefaultLanguageModelTest方法  @0
- 执行aiTest模块的getDefaultLanguageModelTest方法 属性id @2
- 执行aiTest模块的getDefaultLanguageModelTest方法 属性name @GPT-4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 测试场景1:有多个启用模型
$table = zenData('ai_model');
$table->id->range('1-5');
$table->type->range('gpt{2},claude{2},gemini{1}');
$table->vendor->range('openai{2},anthropic{2},google{1}');
$table->name->range('GPT-4,GPT-3.5,Claude-3-Opus,Claude-3-Sonnet,Gemini-Pro');
$table->credentials->range('{"api_key": "test_key_1"}{5}');
$table->enabled->range('1{5}');
$table->deleted->range('0{5}');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->gen(5);

su('admin');

$aiTest = new aiModelTest();

r($aiTest->getDefaultLanguageModelTest()) && p('id') && e('1');

// 测试场景2:只有一个启用模型
zenData('ai_model')->loadYaml('ai_model_getdefaultlanguagemodel', false, 2)->gen(0);
$table = zenData('ai_model');
$table->id->range('5');
$table->type->range('gemini');
$table->vendor->range('google');
$table->name->range('Gemini-Pro');
$table->credentials->range('{"api_key": "test_key_5"}');
$table->enabled->range('1');
$table->deleted->range('0');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-05 10:00:00`');
$table->gen(1);
r($aiTest->getDefaultLanguageModelTest()) && p('id') && e('5');

// 测试场景3:没有任何模型
zenData('ai_model')->loadYaml('ai_model_getdefaultlanguagemodel', false, 2)->gen(0);
r($aiTest->getDefaultLanguageModelTest()) && p() && e('0');

// 测试场景4:所有模型都被禁用
$table = zenData('ai_model');
$table->id->range('1-3');
$table->type->range('gpt,claude,gemini');
$table->vendor->range('openai,anthropic,google');
$table->name->range('GPT-4,Claude-3,Gemini-Pro');
$table->credentials->range('{"api_key": "test_key_1"}{3}');
$table->enabled->range('0{3}');
$table->deleted->range('0{3}');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->gen(3);
r($aiTest->getDefaultLanguageModelTest()) && p() && e('0');

// 测试场景5:有已删除模型,确保不返回已删除的模型
zenData('ai_model')->loadYaml('ai_model_getdefaultlanguagemodel', false, 2)->gen(0);
$table = zenData('ai_model');
$table->id->range('1-3');
$table->type->range('gpt{2},claude');
$table->vendor->range('openai{2},anthropic');
$table->name->range('GPT-4,GPT-3.5,Claude-3');
$table->credentials->range('{"api_key": "test_key_1"}{3}');
$table->enabled->range('1{3}');
$table->deleted->range('1,0{2}');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->gen(3);
r($aiTest->getDefaultLanguageModelTest()) && p('id') && e('2');

// 测试场景6:验证返回的是id升序排序的第一个
zenData('ai_model')->loadYaml('ai_model_getdefaultlanguagemodel', false, 2)->gen(0);
$table = zenData('ai_model');
$table->id->range('3,1,2');
$table->type->range('gemini,gpt,claude');
$table->vendor->range('google,openai,anthropic');
$table->name->range('Gemini-Pro,GPT-4,Claude-3');
$table->credentials->range('{"api_key": "test_key_1"}{3}');
$table->enabled->range('1{3}');
$table->deleted->range('0{3}');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->gen(3);
r($aiTest->getDefaultLanguageModelTest()) && p('name') && e('GPT-4');