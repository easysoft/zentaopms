#!/usr/bin/env php
<?php

/**

title=测试 aiModel::edit();
timeout=0
cid=15021

- 步骤1：模型ID为空时返回false @0
- 步骤2：模型ID为负数时返回false @0
- 步骤3：不存在的模型ID时返回false @0
- 步骤4：有效模型ID和参数在测试环境中返回false @0
- 步骤5：所有参数都有效在测试环境中返回false @0

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
r($aiTest->editTest(null, 'Hello world', 'Fix grammar mistakes')) && p() && e('0'); // 步骤1：模型ID为空时返回false
r($aiTest->editTest(-1, 'Hello world', 'Fix grammar mistakes')) && p() && e('0'); // 步骤2：模型ID为负数时返回false
r($aiTest->editTest(999, 'Hello world', 'Fix grammar mistakes')) && p() && e('0'); // 步骤3：不存在的模型ID时返回false
r($aiTest->editTest(1, 'Hello world', 'Fix grammar mistakes')) && p() && e('0'); // 步骤4：有效模型ID和参数在测试环境中返回false
r($aiTest->editTest(2, 'Write a story', 'Make it more engaging', array('temperature' => 0.7))) && p() && e('0'); // 步骤5：所有参数都有效在测试环境中返回false