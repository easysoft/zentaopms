#!/usr/bin/env php
<?php

/**

title=测试 productZen::responseAfterBatchEdit();
timeout=0
cid=0

- 步骤1：有项目集ID的情况
 - 属性result @success
 - 属性message @保存成功
 - 属性load @test_link_program_product_programID=programID=1
- 步骤2：无项目集ID的情况
 - 属性result @success
 - 属性message @保存成功
 - 属性load @test_link_program_productView
- 步骤3：在产品模块下
 - 属性result @success
 - 属性message @保存成功
 - 属性load @test_link_product_all
- 步骤4：空项目集ID情况
 - 属性result @success
 - 属性message @保存成功
 - 属性load @test_link_program_productView
- 步骤5：负数项目集ID情况
 - 属性result @success
 - 属性message @保存成功
 - 属性load @test_link_program_product_programID=programID=-1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->program->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->type->range('normal{3},branch{2}');
$table->status->range('normal{4},closed{1}');
$table->createdBy->range('admin{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->responseAfterBatchEditTest(1, 'program')) && p('result,message,load') && e('success,保存成功,test_link_program_product_programID=programID=1'); // 步骤1：有项目集ID的情况
r($productTest->responseAfterBatchEditTest(0, 'program')) && p('result,message,load') && e('success,保存成功,test_link_program_productView'); // 步骤2：无项目集ID的情况
r($productTest->responseAfterBatchEditTest(0, 'product')) && p('result,message,load') && e('success,保存成功,test_link_product_all'); // 步骤3：在产品模块下
r($productTest->responseAfterBatchEditTest(0, 'program')) && p('result,message,load') && e('success,保存成功,test_link_program_productView'); // 步骤4：空项目集ID情况
r($productTest->responseAfterBatchEditTest(-1, 'program')) && p('result,message,load') && e('success,保存成功,test_link_program_product_programID=programID=-1'); // 步骤5：负数项目集ID情况