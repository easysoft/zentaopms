#!/usr/bin/env php
<?php

/**

title=测试 productZen::setCreateMenu();
timeout=0
cid=0

- 步骤1：program tab调用setMenuVars功能属性programTabHandled @1
- 步骤2：doc tab移除子菜单功能属性docSubMenuRemoved @1
- 步骤3：非mhtml视图类型直接返回属性nonMhtmlReturn @1
- 步骤4：projectstory模块story方法特殊处理属性projectStoryHandled @1
- 步骤5：常规mhtml视图调用product->setMenu属性productMenuCalled @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->status->range('normal{3},closed{2}');
$table->type->range('normal');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->setCreateMenuTest(1)) && p('programTabHandled') && e('1'); // 步骤1：program tab调用setMenuVars功能
r($productTest->setCreateMenuTest(2)) && p('docSubMenuRemoved') && e('1'); // 步骤2：doc tab移除子菜单功能
r($productTest->setCreateMenuTest(0)) && p('nonMhtmlReturn') && e('1'); // 步骤3：非mhtml视图类型直接返回
r($productTest->setCreateMenuTest(3)) && p('projectStoryHandled') && e('1'); // 步骤4：projectstory模块story方法特殊处理
r($productTest->setCreateMenuTest(4)) && p('productMenuCalled') && e('1'); // 步骤5：常规mhtml视图调用product->setMenu