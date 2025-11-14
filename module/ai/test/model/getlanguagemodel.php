#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getLanguageModel();
timeout=0
cid=15033

- 执行aiTest模块的getLanguageModelTest方法，参数是1, false
 - 属性id @1
 - 属性name @GPT-4
 - 属性enabled @1
- 执行aiTest模块的getLanguageModelTest方法，参数是7, false
 - 属性id @7
 - 属性name @Gemini
 - 属性enabled @0
- 执行aiTest模块的getLanguageModelTest方法，参数是7, true  @0
- 执行aiTest模块的getLanguageModelTest方法，参数是999, false  @0
- 执行aiTest模块的getLanguageModelTest方法，参数是9, false  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_model');
$table->id->range('1-10');
$table->type->range('llm{10}');
$table->vendor->range('openai{3},anthropic{3},google{2},local{2}');
$table->name->range('GPT-4{2},Claude{2},GPT-3.5{2},Gemini{2},Local{2}');
$table->credentials->range('{"key":"test_key"}{10}');
$table->createdBy->range('admin{10}');
$table->createdDate->range('`2023-01-01 10:00:00`,`2023-01-02 10:00:00`,`2023-01-03 10:00:00`,`2023-01-04 10:00:00`,`2023-01-05 10:00:00`,`2023-01-06 10:00:00`,`2023-01-07 10:00:00`,`2023-01-08 10:00:00`,`2023-01-09 10:00:00`,`2023-01-10 10:00:00`');
$table->enabled->range('1{6},0{4}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

su('admin');

$aiTest = new aiTest();

r($aiTest->getLanguageModelTest(1, false)) && p('id,name,enabled') && e('1,GPT-4,1');
r($aiTest->getLanguageModelTest(7, false)) && p('id,name,enabled') && e('7,Gemini,0');
r($aiTest->getLanguageModelTest(7, true)) && p() && e('0');
r($aiTest->getLanguageModelTest(999, false)) && p() && e('0');
r($aiTest->getLanguageModelTest(9, false)) && p() && e('0');