#!/usr/bin/env php
<?php

/**

title=测试 productZen::setMenu4All();
timeout=0
cid=0

- 步骤1：常规视图情况属性normalView @1
- 步骤2：移动视图情况属性mobileView @1
- 步骤3：产品访问权限检查属性hasProducts @1
- 步骤4：URI保存功能属性uriSaved @1
- 步骤5：综合功能测试
 - 属性normalView @1
 - 属性mobileView @1
 - 属性hasProducts @1
 - 属性uriSaved @1

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
r($productTest->setMenu4AllTest()) && p('normalView') && e('1'); // 步骤1：常规视图情况
r($productTest->setMenu4AllTest()) && p('mobileView') && e('1'); // 步骤2：移动视图情况
r($productTest->setMenu4AllTest()) && p('hasProducts') && e('1'); // 步骤3：产品访问权限检查
r($productTest->setMenu4AllTest()) && p('uriSaved') && e('1'); // 步骤4：URI保存功能
r($productTest->setMenu4AllTest()) && p('normalView,mobileView,hasProducts,uriSaved') && e('1,1,1,1'); // 步骤5：综合功能测试