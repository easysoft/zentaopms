#!/usr/bin/env php
<?php

/**

title=测试 aiModel::complete();
timeout=0
cid=15004

- 执行aiTest模块的completeTest方法，参数是999, 'Hello, world!', 512  @0
- 执行aiTest模块的completeTest方法，参数是1, 'Hello, world!', 512  @0
- 执行aiTest模块的completeTest方法，参数是1, '', 512  @0
- 执行aiTest模块的completeTest方法，参数是-1, 'Hello, world!', 512  @0
- 执行aiTest模块的completeTest方法，参数是'invalid', 'Hello, world!', 512  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$ai_model = zenData('ai_model');
$ai_model->id->range('1-5');
$ai_model->type->range('openai{2},ernie{2},claude{1}');
$ai_model->vendor->range('openai{2},baidu{2},anthropic{1}');
$ai_model->credentials->range('{"api_key":"test_key_1"},{"api_key":"test_key_2"},{"api_key":"test_key_3"},{"api_key":"test_key_4"},{"api_key":"test_key_5"}');
$ai_model->name->range('GPT-3.5,GPT-4,ERNIE-Bot,ERNIE-Turbo,Claude-3');
$ai_model->createdBy->range('admin{5}');
$ai_model->createdDate->range('`2024-01-01`{5}');
$ai_model->enabled->range('1{5}');
$ai_model->deleted->range('0{5}');
$ai_model->gen(5);

su('admin');

$aiTest = new aiModelTest();

r($aiTest->completeTest(999, 'Hello, world!', 512)) && p() && e('0');
r($aiTest->completeTest(1, 'Hello, world!', 512)) && p() && e('0');
r($aiTest->completeTest(1, '', 512)) && p() && e('0');
r($aiTest->completeTest(-1, 'Hello, world!', 512)) && p() && e('0');
r($aiTest->completeTest('invalid', 'Hello, world!', 512)) && p() && e('0');