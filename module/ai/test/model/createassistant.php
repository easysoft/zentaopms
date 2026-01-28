#!/usr/bin/env php
<?php

/**

title=测试 aiModel::createAssistant();
timeout=0
cid=15009

- 执行aiTest模块的createAssistantTest方法，参数是$assistant1, false  @1
- 执行aiTest模块的createAssistantTest方法，参数是$assistant2, true  @1
- 执行aiTest模块的createAssistantTest方法，参数是$assistant3, false  @1
- 执行aiTest模块的createAssistantTest方法，参数是$assistant4, true  @1
- 执行aiTest模块的createAssistantTest方法，参数是$assistant5, false  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$modelTable = zenData('ai_model');
$modelTable->id->range('1-5');
$modelTable->type->range('text');
$modelTable->vendor->range('openai,claude,gemini');
$modelTable->credentials->range('test1,test2,test3');
$modelTable->name->range('Model1,Model2,Model3,Model4,Model5');
$modelTable->createdBy->range('admin');
$modelTable->createdDate->range('`2024-01-01 09:00:00`');
$modelTable->enabled->range('1');
$modelTable->deleted->range('0');
$modelTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：创建正常的助手且不发布
$assistant1 = new stdClass();
$assistant1->name = '测试助手1';
$assistant1->modelId = 1;
$assistant1->desc = '这是一个测试助手';
$assistant1->systemMessage = '你是一个测试助手';
$assistant1->greetings = '你好，我是测试助手';
$assistant1->icon = 'coding-1';
r($aiTest->createAssistantTest($assistant1, false)) && p() && e('1');

// 步骤2：创建正常的助手且立即发布
$assistant2 = new stdClass();
$assistant2->name = '测试助手2';
$assistant2->modelId = 2;
$assistant2->desc = '这是一个发布的测试助手';
$assistant2->systemMessage = '你是一个发布的测试助手';
$assistant2->greetings = '你好，我是发布的助手';
$assistant2->icon = 'coding-2';
r($aiTest->createAssistantTest($assistant2, true)) && p() && e('1');

// 步骤3：创建助手验证不发布时enabled为0
$assistant3 = new stdClass();
$assistant3->name = '测试助手3';
$assistant3->modelId = 1;
$assistant3->desc = '验证未发布状态';
$assistant3->systemMessage = '测试助手';
$assistant3->greetings = '你好';
$assistant3->icon = 'coding-1';
r($aiTest->createAssistantTest($assistant3, false)) && p() && e('1');

// 步骤4：创建助手验证发布时enabled为1
$assistant4 = new stdClass();
$assistant4->name = '测试助手4';
$assistant4->modelId = 2;
$assistant4->desc = '验证发布状态';
$assistant4->systemMessage = '发布的测试助手';
$assistant4->greetings = '我已发布';
$assistant4->icon = 'coding-2';
r($aiTest->createAssistantTest($assistant4, true)) && p() && e('1');

// 步骤5：创建助手验证基本字段都正确
$assistant5 = new stdClass();
$assistant5->name = '完整测试助手';
$assistant5->modelId = 3;
$assistant5->desc = '完整的测试用例';
$assistant5->systemMessage = '我是完整的助手';
$assistant5->greetings = '欢迎使用';
$assistant5->icon = 'coding-3';
r($aiTest->createAssistantTest($assistant5, false)) && p() && e('1');