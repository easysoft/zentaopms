#!/usr/bin/env php
<?php

/**

title=测试 aiModel::useLanguageModel();
timeout=0
cid=0

- 步骤1：测试有效模型 @0
- 步骤2：测试禁用模型 @0
- 步骤3：测试不存在模型 @0
- 步骤4：测试空值 @0
- 步骤5：测试null值 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_model');
$table->id->range('1-5');
$table->type->range('chat{3},completion{2}');
$table->vendor->range('openai{3},azure{2}');
$table->credentials->range('{}');
$table->name->range('GPT-4{3},Claude{2}');
$table->desc->range('Test model{5}');
$table->createdBy->range('admin{5}');
$table->createdDate->range('`2024-01-01`');
$table->enabled->range('1{4},0{1}');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->useLanguageModelTest(1)) && p() && e('0'); // 步骤1：测试有效模型
r($aiTest->useLanguageModelTest(5)) && p() && e('0'); // 步骤2：测试禁用模型
r($aiTest->useLanguageModelTest(999)) && p() && e('0'); // 步骤3：测试不存在模型
r($aiTest->useLanguageModelTest('')) && p() && e('0'); // 步骤4：测试空值
r($aiTest->useLanguageModelTest(null)) && p() && e('0'); // 步骤5：测试null值