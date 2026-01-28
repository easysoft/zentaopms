#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getAssistants();
timeout=0
cid=15027

- 执行aiTest模块的getAssistantsTest方法  @10
- 执行aiTest模块的getAssistantsTest方法，参数是null, 'id_desc' 第10条的name属性 @助手10
- 执行aiTest模块的getAssistantsTest方法，参数是null, 'id_desc' 第9条的name属性 @助手9
- 执行aiTest模块的getAssistantsTest方法，参数是null, 'name_asc' 第1条的name属性 @助手1
- 执行aiTest模块的getAssistantsTest方法 第10条的id属性 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_assistant');
$table->id->range('1-10');
$table->name->range('助手1,助手2,助手3,助手4,助手5,助手6,助手7,助手8,助手9,助手10');
$table->modelId->range('1-5');
$table->desc->range('这是一个AI助手的描述{10}');
$table->systemMessage->range('你是一个专业的AI助手{10}');
$table->greetings->range('你好！我是你的AI助手{10}');
$table->icon->range('coding-1');
$table->enabled->range('1');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`,`2024-01-06 10:00:00`,`2024-01-07 10:00:00`,`2024-01-08 10:00:00`,`2024-01-09 10:00:00`,`2024-01-10 10:00:00`');
$table->publishedDate->range('`2024-01-05 10:00:00`');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$aiTest = new aiModelTest();

r(count($aiTest->getAssistantsTest())) && p() && e('10');
r($aiTest->getAssistantsTest(null, 'id_desc')) && p('10:name') && e('助手10');
r($aiTest->getAssistantsTest(null, 'id_desc')) && p('9:name') && e('助手9');
r($aiTest->getAssistantsTest(null, 'name_asc')) && p('1:name') && e('助手1');
r($aiTest->getAssistantsTest()) && p('10:id') && e('10');