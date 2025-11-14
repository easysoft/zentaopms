#!/usr/bin/env php
<?php

/**

title=测试 actionZen::recoverObject();
timeout=0
cid=14973

- 步骤1：名称和代码都重复时恢复对象
 - 属性name @产品1_1
 - 属性code @product1_1
- 步骤2：仅名称重复时恢复对象属性name @产品2_1
- 步骤3：仅代码重复时恢复对象属性code @product3_1
- 步骤4：无重复时恢复对象 @no_change
- 步骤5：空字符串参数时恢复对象 @no_change

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('action');
$table->id->range('1-10');
$table->objectType->range('product');
$table->objectID->range('1-10');
$table->actor->range('admin');
$table->action->range('deleted');
$table->date->range('`2023-01-01 00:00:00`');
$table->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->code->range('product1,product2,product3,product4,product5');
$productTable->deleted->range('1');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($actionTest->recoverObjectTest('产品1', 'product1', '产品1_1', 'product1_1', 'both')) && p('name,code') && e('产品1_1,product1_1'); // 步骤1：名称和代码都重复时恢复对象
r($actionTest->recoverObjectTest('产品2', '', '产品2_1', '', 'name')) && p('name') && e('产品2_1'); // 步骤2：仅名称重复时恢复对象
r($actionTest->recoverObjectTest('', 'product3', '', 'product3_1', 'code')) && p('code') && e('product3_1'); // 步骤3：仅代码重复时恢复对象
r($actionTest->recoverObjectTest('产品4', 'product4', '', '', 'none')) && p() && e('no_change'); // 步骤4：无重复时恢复对象
r($actionTest->recoverObjectTest('', '', '', '', 'empty')) && p() && e('no_change'); // 步骤5：空字符串参数时恢复对象