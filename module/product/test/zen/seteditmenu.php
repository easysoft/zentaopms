#!/usr/bin/env php
<?php

/**

title=测试 productZen::setEditMenu();
timeout=0
cid=0

- 步骤1：项目集ID存在时调用setMenuVars功能属性setMenuVarsCalled @1
- 步骤2：项目集ID不存在时调用产品菜单设置属性productMenuSet @1
- 步骤3：参数有效性验证属性paramsValid @1
- 步骤4：条件分支逻辑验证属性branchLogic @1
- 步骤5：方法执行完整性验证属性methodCompleted @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('1-5,0{5}');
$table->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$table->status->range('normal{8},closed{2}');
$table->type->range('normal');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->setEditMenuTest(1, 1)) && p('setMenuVarsCalled') && e('1'); // 步骤1：项目集ID存在时调用setMenuVars功能
r($productTest->setEditMenuTest(1, 0)) && p('productMenuSet') && e('1'); // 步骤2：项目集ID不存在时调用产品菜单设置
r($productTest->setEditMenuTest(1, 2)) && p('paramsValid') && e('1'); // 步骤3：参数有效性验证
r($productTest->setEditMenuTest(2, 3)) && p('branchLogic') && e('1'); // 步骤4：条件分支逻辑验证
r($productTest->setEditMenuTest(3, 0)) && p('methodCompleted') && e('1'); // 步骤5：方法执行完整性验证