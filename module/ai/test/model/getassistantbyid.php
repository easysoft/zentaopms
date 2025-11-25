#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getAssistantById();
timeout=0
cid=15026

- 步骤1：查询存在的助手ID
 - 属性id @1
 - 属性name @AI助手1
- 步骤2：查询不存在的助手ID @0
- 步骤3：测试边界值ID为0 @0
- 步骤4：测试负数ID @0
- 步骤5：测试字符串ID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备
$table = zenData('ai_assistant');
$table->id->range('1-10');
$table->name->range('AI助手1,AI助手2,代码助手1,代码助手2,通用助手1,通用助手2,AI助手3,代码助手3,通用助手3,AI助手4');
$table->modelId->range('1-3');
$table->desc->range('这是一个AI助手,这是一个代码助手,这是一个通用助手');
$table->systemMessage->range('你是一个AI助手,你是一个代码助手,你是一个通用助手');
$table->greetings->range('你好！我是AI助手,你好！我是代码助手,你好！我是通用助手');
$table->icon->range('coding-1,ai-1,robot-1');
$table->enabled->range('0,1');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`,`2024-01-06 10:00:00`,`2024-01-07 10:00:00`,`2024-01-08 10:00:00`,`2024-01-09 10:00:00`,`2024-01-10 10:00:00`');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiTest();

// 5. 执行测试步骤（必须至少5个）
r($aiTest->getAssistantByIdTest(1)) && p('id,name') && e('1,AI助手1'); // 步骤1：查询存在的助手ID
r($aiTest->getAssistantByIdTest(999)) && p() && e('0'); // 步骤2：查询不存在的助手ID
r($aiTest->getAssistantByIdTest(0)) && p() && e('0'); // 步骤3：测试边界值ID为0
r($aiTest->getAssistantByIdTest(-1)) && p() && e('0'); // 步骤4：测试负数ID
r($aiTest->getAssistantByIdTest('abc')) && p() && e('0'); // 步骤5：测试字符串ID