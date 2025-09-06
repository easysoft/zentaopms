#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getAssistantsByModel();
timeout=0
cid=0

- 步骤1：获取模型ID为1且启用的助手 @3
- 步骤2：获取模型ID为2且启用的助手 @3
- 步骤3：获取模型ID为1且未启用的助手 @0
- 步骤4：获取不存在的模型ID启用助手 @0
- 步骤5：获取模型ID为3未启用的助手（排除已删除） @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_assistant');
$table->id->range('1-10');
$table->name->range('Assistant1,Assistant2,Assistant3,TestAssistant1,TestAssistant2,Helper1,Helper2,AI助手1,AI助手2,删除助手');
$table->modelId->range('1{3},2{3},3{2},999{2}');
$table->desc->range('Description for assistant');
$table->systemMessage->range('You are a helpful assistant');
$table->greetings->range('Hello! How can I help you?');
$table->icon->range('coding-1');
$table->enabled->range('1{6},0{4}');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->publishedDate->range('[]{10}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->getAssistantsByModelTest(1, true)) && p() && e(3); // 步骤1：获取模型ID为1且启用的助手
r($aiTest->getAssistantsByModelTest(2, true)) && p() && e(3); // 步骤2：获取模型ID为2且启用的助手  
r($aiTest->getAssistantsByModelTest(1, false)) && p() && e(0); // 步骤3：获取模型ID为1且未启用的助手
r($aiTest->getAssistantsByModelTest(999, true)) && p() && e(0); // 步骤4：获取不存在的模型ID启用助手
r($aiTest->getAssistantsByModelTest(3, false)) && p() && e(2); // 步骤5：获取模型ID为3未启用的助手（排除已删除）