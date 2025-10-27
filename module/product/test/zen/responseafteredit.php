#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseAfterEdit();
timeout=0
cid=0

- 步骤1：正常产品编辑，无钩子消息
 - 属性result @success
 - 属性load @test_link_product_view
- 步骤2：程序集产品编辑，无钩子消息
 - 属性result @success
 - 属性load @test_link_program_product
- 步骤3：正常产品编辑，有钩子消息
 - 属性result @success
 - 属性message @自定义钩子消息
- 步骤4：程序集产品编辑，有钩子消息
 - 属性result @success
 - 属性load @test_link_program_product
- 步骤5：无效productID测试属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品A,产品B,产品C');
$table->status->range('normal');
$table->program->range('0,1,2');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->responseAfterEditTest(1, 0, '')) && p('result,load') && e('success,test_link_product_view'); // 步骤1：正常产品编辑，无钩子消息
r($productTest->responseAfterEditTest(2, 1, '')) && p('result,load') && e('success,test_link_program_product'); // 步骤2：程序集产品编辑，无钩子消息
r($productTest->responseAfterEditTest(3, 0, '自定义钩子消息')) && p('result,message') && e('success,自定义钩子消息'); // 步骤3：正常产品编辑，有钩子消息
r($productTest->responseAfterEditTest(4, 2, '钩子执行成功')) && p('result,load') && e('success,test_link_program_product'); // 步骤4：程序集产品编辑，有钩子消息
r($productTest->responseAfterEditTest(0, 0, '')) && p('result') && e('success'); // 步骤5：无效productID测试