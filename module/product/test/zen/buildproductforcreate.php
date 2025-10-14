#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForCreate();
timeout=0
cid=0

- 步骤1：正常工作流组参数属性vision @rnd
- 步骤2：零值工作流组参数属性vision @rnd
- 步骤3：负数工作流组参数属性vision @rnd
- 步骤4：检查返回对象包含必要字段属性name @测试产品
- 步骤5：验证ACL逻辑和图片URL处理属性acl @open

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->program->range('0,1,2');
$table->status->range('normal,closed');
$table->type->range('normal,branch,platform');
$table->PO->range('admin,user1,user2');
$table->acl->range('open,private,custom');
$table->vision->range('rnd,lite,or');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildProductForCreateTest(1)) && p('vision') && e('rnd'); // 步骤1：正常工作流组参数
r($productTest->buildProductForCreateTest(0)) && p('vision') && e('rnd'); // 步骤2：零值工作流组参数
r($productTest->buildProductForCreateTest(-1)) && p('vision') && e('rnd'); // 步骤3：负数工作流组参数
r($productTest->buildProductForCreateTest(2)) && p('name') && e('测试产品'); // 步骤4：检查返回对象包含必要字段
r($productTest->buildProductForCreateTest(5)) && p('acl') && e('open'); // 步骤5：验证ACL逻辑和图片URL处理