#!/usr/bin/env php
<?php

/**

title=测试 aiModel::edit();
timeout=0
cid=0

- 步骤1：正常情况（模拟无模型配置返回错误信息） @0
- 步骤2：不存在的模型ID @0
- 步骤3：空输入文本 @0
- 步骤4：空编辑指令 @0
- 步骤5：带选项参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备
$table = zenData('ai_model');
$table->name->range('test-model{1}, gpt-3.5{1}, invalid-model{1}');
$table->type->range('text{2}, chat{1}');
$table->vendor->range('openai{2}, custom{1}');
$table->credentials->range('{"api_key":"test_key"}{3}');
$table->createdBy->range('admin{3}');
$table->createdDate->range('`2024-01-01 10:00:00`');
$table->enabled->range('1{2}, 0{1}');
$table->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$aiTest = new aiTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($aiTest->editTest(1, 'Hello world', 'Fix grammar mistakes')) && p() && e('0'); // 步骤1：正常情况（模拟无模型配置返回错误信息）
r($aiTest->editTest(999, 'Hello world', 'Fix grammar mistakes')) && p() && e('0'); // 步骤2：不存在的模型ID
r($aiTest->editTest(1, '', 'Fix grammar mistakes')) && p() && e('0'); // 步骤3：空输入文本
r($aiTest->editTest(1, 'Hello world', '')) && p() && e('0'); // 步骤4：空编辑指令
r($aiTest->editTest(1, 'Hello world', 'Fix grammar mistakes', array('temperature' => 0.7))) && p() && e('0'); // 步骤5：带选项参数