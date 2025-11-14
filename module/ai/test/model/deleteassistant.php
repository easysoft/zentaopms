#!/usr/bin/env php
<?php

/**

title=测试 aiModel::deleteAssistant();
timeout=0
cid=15016

- 执行aiTest模块的deleteAssistantTest方法，参数是1  @1
- 执行aiTest模块的deleteAssistantTest方法，参数是2  @1
- 执行aiTest模块的deleteAssistantTest方法，参数是999  @1
- 执行aiTest模块的deleteAssistantTest方法，参数是5  @1
- 执行aiTest模块的deleteAssistantTest方法，参数是-1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_assistant');
$table->name->range('AI助手1,AI助手2,AI助手3,AI助手4,AI助手5');
$table->modelId->range('1-5');
$table->desc->range('这是一个AI助手');
$table->systemMessage->range('你是一个专业的AI助手');
$table->greetings->range('你好，我是AI助手');
$table->icon->range('coding-1');
$table->enabled->range('1');
$table->createdDate->range('`2023-01-01 00:00:00`');
$table->publishedDate->range('`2023-01-01 00:00:00`,`0000-00-00 00:00:00`');
$table->deleted->range('0{4},1{1}');
$table->gen(5);
zenData('action')->gen(0);

su('admin');

$aiTest = new aiTest();

r($aiTest->deleteAssistantTest(1)) && p() && e('1');
r($aiTest->deleteAssistantTest(2)) && p() && e('1');
r($aiTest->deleteAssistantTest(999)) && p() && e('1');
r($aiTest->deleteAssistantTest(5)) && p() && e('1');
r($aiTest->deleteAssistantTest(-1)) && p() && e('0');