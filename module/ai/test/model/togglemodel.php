#!/usr/bin/env php
<?php

/**

title=测试 aiModel::toggleModel();
timeout=0
cid=15070

- 步骤1：启用已禁用的模型 @1
- 步骤2：禁用已启用的模型 @1
- 步骤3：对不存在的模型ID进行切换 @1
- 步骤4：测试边界值ID为0 @1
- 步骤5：测试负数ID @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('ai_model');
$table->id->range('1-5');
$table->type->range('chat');
$table->vendor->range('openai,zhipu,qwen,deepseek,openai');
$table->credentials->range('{"api_key":"test-key-1"},{"api_key":"test-key-2"},{"api_key":"test-key-3"},{"api_key":"test-key-4"},{"api_key":"test-key-5"}');
$table->name->range('GPT-4,ChatGLM,通义千问,DeepSeek,GPT-3.5');
$table->desc->range('OpenAI GPT-4模型,智谱AI模型,阿里通义千问,DeepSeek模型,OpenAI GPT-3.5');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`,`2024-01-04 10:00:00`,`2024-01-05 10:00:00`');
$table->enabled->range('0,1,1,0,1');
$table->deleted->range('0');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiModelTest();

// 5. 执行测试步骤（必须至少5个）
r($aiTest->toggleModelTest(1, true)) && p() && e('1'); // 步骤1：启用已禁用的模型
r($aiTest->toggleModelTest(2, false)) && p() && e('1'); // 步骤2：禁用已启用的模型
r($aiTest->toggleModelTest(999, true)) && p() && e('1'); // 步骤3：对不存在的模型ID进行切换
r($aiTest->toggleModelTest(0, true)) && p() && e('1'); // 步骤4：测试边界值ID为0
r($aiTest->toggleModelTest(-1, false)) && p() && e('1'); // 步骤5：测试负数ID