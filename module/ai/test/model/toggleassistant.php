#!/usr/bin/env php
<?php

/**

title=测试 aiModel::toggleAssistant();
timeout=0
cid=15069

- 步骤1：启用已禁用的助手 @1
- 步骤2：禁用已启用的助手 @1
- 步骤3：对不存在的助手ID进行切换 @1
- 步骤4：测试边界值ID为0 @1
- 步骤5：测试另一个有效ID @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('ai_assistant');
$table->id->range('1-5');
$table->name->range('测试助手1,测试助手2,代码助手1,代码助手2,通用助手1');
$table->modelId->range('1-3');
$table->desc->range('这是一个测试助手,这是一个代码助手,这是一个通用助手');
$table->systemMessage->range('你是一个测试助手,你是一个代码助手,你是一个通用助手');
$table->greetings->range('你好！我是测试助手,你好！我是代码助手,你好！我是通用助手');
$table->icon->range('coding-1,ai-1,robot-1');
$table->enabled->range('0,1,0,1,0');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`');
// $table->publishedDate will be set by toggleAssistant method
$table->deleted->range('0');
$table->gen(5);

$modelTable = zenData('ai_model');
$modelTable->id->range('1-3');
$modelTable->name->range('test-model-1,test-model-2,test-model-3');
$modelTable->type->range('chat');
$modelTable->vendor->range('openai');
$modelTable->credentials->range('{"api_key":"test-key"}');
$modelTable->createdBy->range('admin');
$modelTable->createdDate->range('`2024-01-01 10:00:00`');
$modelTable->deleted->range('0');
$modelTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiModelTest();

// 5. 执行测试步骤（必须至少5个）
r($aiTest->toggleAssistantTest(1, true)) && p() && e('1'); // 步骤1：启用已禁用的助手
r($aiTest->toggleAssistantTest(2, false)) && p() && e('1'); // 步骤2：禁用已启用的助手
r($aiTest->toggleAssistantTest(999, true)) && p() && e('1'); // 步骤3：对不存在的助手ID进行切换
r($aiTest->toggleAssistantTest(0, true)) && p() && e('1'); // 步骤4：测试边界值ID为0
r($aiTest->toggleAssistantTest(5, true)) && p() && e('1'); // 步骤5：测试另一个有效ID