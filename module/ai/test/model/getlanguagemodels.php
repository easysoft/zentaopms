#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLanguageModels();
timeout=0
cid=15035

- 执行aiTest模块的getLanguageModelsTest方法  @8
- 执行aiTest模块的getLanguageModelsTest方法，参数是'gpt'  @2
- 执行aiTest模块的getLanguageModelsTest方法，参数是'', true  @7
- 执行aiTest模块的getLanguageModelsTest方法，参数是'', false, null, 'id_asc' 第1条的id属性 @1
- 执行aiTest模块的getLanguageModelsTest方法，参数是'nonexistent'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_model');
$table->id->range('1-10');
$table->type->range('gpt,claude,gemini{2},llama{3}');
$table->vendor->range('openai,anthropic,google{2},meta{3}');
$table->name->range('GPT-4,Claude-3,Gemini-Pro{2},LLaMA-2{3}');
$table->credentials->range('{"api_key": "test_key_1"},{"api_key": "test_key_2"}{9}');
$table->enabled->range('1{7},0{3}');
$table->deleted->range('0{8},1{2}');
$table->createdBy->range('admin{5},user{5}');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`,`2024-01-06 10:00:00`,`2024-01-07 10:00:00`,`2024-01-08 10:00:00`,`2024-01-09 10:00:00`,`2024-01-10 10:00:00`');
$table->gen(10);

su('admin');

$aiTest = new aiModelTest();

r(count($aiTest->getLanguageModelsTest())) && p() && e('8');
r(count($aiTest->getLanguageModelsTest('gpt'))) && p() && e('2');
r(count($aiTest->getLanguageModelsTest('', true))) && p() && e('7');
r($aiTest->getLanguageModelsTest('', false, null, 'id_asc')) && p('1:id') && e('1');
r($aiTest->getLanguageModelsTest('nonexistent')) && p() && e('0');