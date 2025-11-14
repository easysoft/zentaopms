#!/usr/bin/env php
<?php

/**

title=测试 aiModel::updateCustomCategories();
timeout=0
cid=15076

- 步骤1：空数据处理 @0
- 步骤2：字符串值处理 @0
- 步骤3：数组值处理 @0
- 步骤4：混合类型处理 @0
- 步骤5：过滤空值处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('config');
$table->module->range('ai{5}');
$table->section->range('miniProgram{5}');
$table->owner->range('system{5}');
$table->vision->range('[]{5}');
$table->key->range('existing1,existing2,existing3,existing4,existing5');
$table->value->range('value1,value2,value3,value4,value5');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 清除POST数据
$_POST = array();

// 步骤1：POST数据为空时的处理
r($aiTest->updateCustomCategoriesTest()) && p() && e('0'); // 步骤1：空数据处理

// 步骤2：字符串值的配置更新
$_POST = array('category1' => 'string_value', 'category2' => 'another_string');
r($aiTest->updateCustomCategoriesTest()) && p() && e('0'); // 步骤2：字符串值处理

// 步骤3：数组值的配置更新
$_POST = array('array_category' => array('value1', 'value2', 'value3'));
r($aiTest->updateCustomCategoriesTest()) && p() && e('0'); // 步骤3：数组值处理

// 步骤4：混合数据类型的配置更新
$_POST = array('string_key' => 'string_value', 'array_key' => array('array_value1', 'array_value2'));
r($aiTest->updateCustomCategoriesTest()) && p() && e('0'); // 步骤4：混合类型处理

// 步骤5：空数组过滤处理
$_POST = array('empty_string' => '', 'empty_array' => array(), 'valid_key' => 'valid_value', 'array_with_empty' => array('', 'valid', ''));
r($aiTest->updateCustomCategoriesTest()) && p() && e('0'); // 步骤5：过滤空值处理