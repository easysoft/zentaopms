#!/usr/bin/env php
<?php

/**

title=测试 aiModel::converseTwiceForJSON();
timeout=0
cid=0

- 执行aiTest模块的converseTwiceForJSONTest方法，参数是1, $validMessages, $validSchema, $validOptions  @false
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是0, $validMessages, $validSchema, $validOptions  @false
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是1, array  @false
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是1, $validMessages, null, $validOptions  @false
- 执行aiTest模块的converseTwiceForJSONTest方法，参数是999, $validMessages, $validSchema, $validOptions  @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_model');
$table->id->range('1-5');
$table->type->range('openai-gpt35,openai-gpt4,baidu-ernie,anthropic-claude,zhipu-glm');
$table->vendor->range('openai,openai,baidu,anthropic,zhipu');
$table->credentials->range('{"key": "sk-test123"},{"key": "sk-test456"},{"key": "ak-test789"},{"key": "ak-test000"},{"key": "test-key"}');
$table->proxy->range('[]');
$table->name->range('OpenAI GPT-3.5,OpenAI GPT-4,Baidu ERNIE,Anthropic Claude,ZhiPu GLM');
$table->desc->range('GPT-3.5 Turbo,GPT-4 Model,ERNIE Bot,Claude 3 Haiku,ChatGLM Model');
$table->createdBy->range('admin{5}');
$table->createdDate->range('2023-01-01 10:00:00{5}');
$table->editedBy->range('[]');
$table->editedDate->range('[]');
$table->enabled->range('1{5}');
$table->deleted->range('0{5}');
$table->gen(5);

su('admin');

$aiTest = new aiTest();

$validMessages = array(
    (object)array('role' => 'user', 'content' => 'Please analyze this data and provide structured output')
);

$validSchema = (object)array(
    'type' => 'object',
    'properties' => (object)array(
        'result' => (object)array('type' => 'string'),
        'status' => (object)array('type' => 'string')
    ),
    'required' => array('result', 'status')
);

$validOptions = array('temperature' => 0.7, 'max_tokens' => 100);

r($aiTest->converseTwiceForJSONTest(1, $validMessages, $validSchema, $validOptions)) && p() && e('false');
r($aiTest->converseTwiceForJSONTest(0, $validMessages, $validSchema, $validOptions)) && p() && e('false');
r($aiTest->converseTwiceForJSONTest(1, array(), $validSchema, $validOptions)) && p() && e('false');
r($aiTest->converseTwiceForJSONTest(1, $validMessages, null, $validOptions)) && p() && e('false');
r($aiTest->converseTwiceForJSONTest(999, $validMessages, $validSchema, $validOptions)) && p() && e('false');