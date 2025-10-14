#!/usr/bin/env php
<?php

/**

title=测试 productZen::setProjectMenu();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性branchLogic @1
 - 属性paramsValid @1
- 步骤2：分支逻辑处理
 - 属性branchLogic @1
 - 属性paramsValid @1
- 步骤3：Cookie设置验证
 - 属性cookieSet @1
 - 属性paramsValid @1
- 步骤4：Session设置验证
 - 属性sessionSet @1
 - 属性paramsValid @1
- 步骤5：边界值测试
 - 属性branchLogic @1
 - 属性paramsValid @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->code->range('product1,product2,product3,product4,product5,product6,product7,product8,product9,product10');
$table->status->range('normal');
$table->PO->range('admin');
$table->program->range('1-3:R');
$table->type->range('normal');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($productTest->setProjectMenuTest(1, 'main', '')) && p('branchLogic,paramsValid') && e('1,1'); // 步骤1：正常情况
r($productTest->setProjectMenuTest(2, '', 'dev')) && p('branchLogic,paramsValid') && e('1,1'); // 步骤2：分支逻辑处理
r($productTest->setProjectMenuTest(3, 'release', '')) && p('cookieSet,paramsValid') && e('1,1'); // 步骤3：Cookie设置验证
r($productTest->setProjectMenuTest(4, 'master', 'feature')) && p('sessionSet,paramsValid') && e('1,1'); // 步骤4：Session设置验证
r($productTest->setProjectMenuTest(0, 'main', '')) && p('branchLogic,paramsValid') && e('1,0'); // 步骤5：边界值测试