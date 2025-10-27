#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForEdit();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性name @编辑的测试产品
 - 属性PO @admin
 - 属性type @normal
- 步骤2：工作流分组
 - 属性name @编辑的测试产品
 - 属性status @normal
 - 属性acl @open
- 步骤3：无效ID
 - 属性name @编辑的测试产品
 - 属性QD @admin
 - 属性RD @admin
- 步骤4：边界值ID
 - 属性name @编辑的测试产品
 - 属性acl @open
 - 属性desc @这是一个编辑的测试产品
- 步骤5：大数值ID
 - 属性name @编辑的测试产品
 - 属性type @normal
 - 属性status @normal

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->program->range('0');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->PO->range('admin,user1,user2');
$table->QD->range('admin,user1,user2');
$table->RD->range('admin,user1,user2');
$table->type->range('normal,branch');
$table->status->range('normal,closed');
$table->desc->range('产品描述1,产品描述2,产品描述3');
$table->acl->range('open,private,custom');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-01-01 00:00:00`');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildProductForEditTest(1, 0)) && p('name,PO,type') && e('编辑的测试产品,admin,normal'); // 步骤1：正常情况
r($productTest->buildProductForEditTest(2, 1)) && p('name,status,acl') && e('编辑的测试产品,normal,open'); // 步骤2：工作流分组
r($productTest->buildProductForEditTest(999, 0)) && p('name,QD,RD') && e('编辑的测试产品,admin,admin'); // 步骤3：无效ID
r($productTest->buildProductForEditTest(0, 0)) && p('name,acl,desc') && e('编辑的测试产品,open,这是一个编辑的测试产品'); // 步骤4：边界值ID
r($productTest->buildProductForEditTest(99999, 5)) && p('name,type,status') && e('编辑的测试产品,normal,normal'); // 步骤5：大数值ID