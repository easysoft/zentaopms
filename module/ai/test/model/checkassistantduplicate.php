#!/usr/bin/env php
<?php

/**

title=测试 aiModel::checkAssistantDuplicate();
timeout=0
cid=15000

- 执行aiTest模块的checkAssistantDuplicateTest方法，参数是'Assistant1', 1 
 - 属性name @Assistant1
 - 属性modelId @1
- 执行aiTest模块的checkAssistantDuplicateTest方法，参数是'NonExistentAssistant', 1  @0
- 执行aiTest模块的checkAssistantDuplicateTest方法，参数是'Assistant1', 99  @0
- 执行aiTest模块的checkAssistantDuplicateTest方法，参数是'', 1  @0
- 执行aiTest模块的checkAssistantDuplicateTest方法，参数是'DeletedAssistant', 1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_assistant');
$table->id->range('1-5');
$table->name->range('Assistant1{2},Assistant2,Assistant3,DeletedAssistant');
$table->modelId->range('1{3},2{2}');
$table->desc->range('Test assistant description');
$table->systemMessage->range('You are a helpful assistant');
$table->greetings->range('Hello! How can I help you today?');
$table->icon->range('coding-1');
$table->enabled->range('1');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->publishedDate->range('`2024-01-01 10:00:00`');
$table->deleted->range('0{4},1');
$table->gen(5);

su('admin');

$aiTest = new aiTest();

r($aiTest->checkAssistantDuplicateTest('Assistant1', 1)) && p('name,modelId') && e('Assistant1,1');
r($aiTest->checkAssistantDuplicateTest('NonExistentAssistant', 1)) && p() && e('0');
r($aiTest->checkAssistantDuplicateTest('Assistant1', 99)) && p() && e('0');
r($aiTest->checkAssistantDuplicateTest('', 1)) && p() && e('0');
r($aiTest->checkAssistantDuplicateTest('DeletedAssistant', 1)) && p() && e('0');