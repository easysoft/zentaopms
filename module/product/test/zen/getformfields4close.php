#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Close();
timeout=0
cid=0

- 步骤1：验证status字段类型第status条的type属性 @string
- 步骤2：验证status字段控制类型第status条的control属性 @hidden
- 步骤3：验证status字段默认值第status条的default属性 @close
- 步骤4：验证comment字段类型第comment条的type属性 @string
- 步骤5：验证comment字段控制类型第comment条的control属性 @editor

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$table->status->range('normal{5},closed{3},normal{2}');
$table->program->range('0-2');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getFormFields4CloseTest()) && p('status:type') && e('string'); // 步骤1：验证status字段类型
r($productTest->getFormFields4CloseTest()) && p('status:control') && e('hidden'); // 步骤2：验证status字段控制类型
r($productTest->getFormFields4CloseTest()) && p('status:default') && e('close'); // 步骤3：验证status字段默认值
r($productTest->getFormFields4CloseTest()) && p('comment:type') && e('string'); // 步骤4：验证comment字段类型
r($productTest->getFormFields4CloseTest()) && p('comment:control') && e('editor'); // 步骤5：验证comment字段控制类型