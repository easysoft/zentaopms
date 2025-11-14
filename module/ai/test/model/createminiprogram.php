#!/usr/bin/env php
<?php

/**

title=测试 aiModel::createMiniProgram();
timeout=0
cid=15010

- 步骤1：正常创建小程序 @1
- 步骤2：创建带自定义字段的小程序 @2
- 步骤3：创建带图标配置的小程序 @3
- 步骤4：创建已发布的小程序 @4
- 步骤5：创建空数据的小程序，使用默认值 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备
$table = zenData('ai_model');
$table->id->range('1-10');
$table->name->range('测试模型{1-10}');
$table->type->range('llm');
$table->vendor->range('openai,anthropic,google');
$table->credentials->range('{"api_key":"test_key_{1-10}"}');
$table->deleted->range('0');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->gen(5);

// 清理现有数据
$table = zenData('ai_miniprogram');
$table->gen(0);

$table = zenData('ai_miniprogramfield');
$table->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 测试步骤
$normalData = new stdClass();
$normalData->name = '测试小程序';
$normalData->category = 'personal';
$normalData->desc = '这是一个测试用的小程序描述';
$normalData->model = '1';
$normalData->prompt = '这是一个测试提示词';

r($aiTest->createMiniProgramTest($normalData)) && p() && e('1'); // 步骤1：正常创建小程序

$dataWithFields = new stdClass();
$dataWithFields->name = '带字段的小程序';
$dataWithFields->category = 'work';
$dataWithFields->desc = '这是一个带自定义字段的小程序';
$dataWithFields->model = '2';
$dataWithFields->prompt = '这是提示词';
$dataWithFields->fields = array();
$field1 = new stdClass();
$field1->name = '自定义字段1';
$field1->type = 'text';
$field1->placeholder = '请输入内容';
$field1->required = '1';
$dataWithFields->fields[] = $field1;

r($aiTest->createMiniProgramTest($dataWithFields)) && p() && e('2'); // 步骤2：创建带自定义字段的小程序

$dataWithIcon = new stdClass();
$dataWithIcon->name = '带图标的小程序';
$dataWithIcon->category = 'creative';
$dataWithIcon->desc = '这是一个带图标配置的小程序';
$dataWithIcon->model = '3';
$dataWithIcon->prompt = '提示词内容';
$dataWithIcon->iconName = 'star';
$dataWithIcon->iconTheme = '5';

r($aiTest->createMiniProgramTest($dataWithIcon)) && p() && e('3'); // 步骤3：创建带图标配置的小程序

$publishedData = new stdClass();
$publishedData->name = '已发布小程序';
$publishedData->category = 'business';
$publishedData->desc = '这是一个已发布的小程序';
$publishedData->model = '1';
$publishedData->prompt = '已发布小程序提示词';
$publishedData->published = '1';

r($aiTest->createMiniProgramTest($publishedData)) && p() && e('4'); // 步骤4：创建已发布的小程序

r($aiTest->createMiniProgramTest()) && p() && e('5'); // 步骤5：创建空数据的小程序，使用默认值