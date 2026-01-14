#!/usr/bin/env php
<?php

/**

title=测试 aiModel::updateAssistant();
timeout=0
cid=15075

- 执行aiTest模块的updateAssistantTest方法  @1
- 执行aiTest模块的updateAssistantTest方法  @1
- 执行aiTest模块的updateAssistantTest方法  @1
- 执行aiTest模块的updateAssistantTest方法  @1
- 执行aiTest模块的updateAssistantTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_assistant');
$table->id->range('1-5');
$table->name->range('测试助手1,测试助手2,测试助手3,测试助手4,测试助手5');
$table->modelId->range('1{5}');
$table->desc->range('这是测试助手1的描述,这是测试助手2的描述,这是测试助手3的描述,这是测试助手4的描述,这是测试助手5的描述');
$table->systemMessage->range('你是测试助手1,你是测试助手2,你是测试助手3,你是测试助手4,你是测试助手5');
$table->greetings->range('你好，我是测试助手1,你好，我是测试助手2,你好，我是测试助手3,你好，我是测试助手4,你好，我是测试助手5');
$table->icon->range('coding-1{5}');
$table->enabled->range('1,1,1,0,0');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->deleted->range('0');
$table->gen(5);

$modelTable = zenData('ai_model');
$modelTable->id->range('1-2');
$modelTable->name->range('test-model-1,test-model-2');
$modelTable->type->range('chat');
$modelTable->vendor->range('openai');
$modelTable->credentials->range('{"api_key":"test-key"}');
$modelTable->createdBy->range('admin');
$modelTable->createdDate->range('`2023-01-01 09:00:00`');
$modelTable->enabled->range('1');
$modelTable->deleted->range('0');
$modelTable->gen(2);

su('admin');

$aiTest = new aiModelTest();

r($aiTest->updateAssistantTest((object)array('id' => 1, 'name' => '更新后的助手', 'desc' => '更新后的描述'))) && p() && e('1');
r($aiTest->updateAssistantTest((object)array('id' => 1, 'enabled' => '0'))) && p() && e('1');
r($aiTest->updateAssistantTest((object)array('id' => 2, 'enabled' => '1'))) && p() && e('1');
r($aiTest->updateAssistantTest((object)array('id' => 1, 'name' => '再次更新名称'))) && p() && e('1');
r($aiTest->updateAssistantTest((object)array('id' => 3, 'name' => '第三个助手更新'))) && p() && e('1');