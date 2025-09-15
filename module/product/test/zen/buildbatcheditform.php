#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildBatchEditForm();
timeout=0
cid=0

- 步骤1：正常情况，项目集为0，单个产品
 - 属性products @1
 - 属性programID @0
- 步骤2：正常情况，项目集为1，多个产品
 - 属性products @3
 - 属性programID @1
- 步骤3：边界值，空产品ID列表属性products @0
- 步骤4：边界值，无效产品ID列表属性products @0
- 步骤5：验证项目集数据结构
 - 属性authPrograms @0
 - 属性unauthPrograms @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->program->range('0,1,1,2,0');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('code1,code2,code3,code4,code5');
$table->type->range('normal');
$table->status->range('normal');
$table->desc->range('产品描述1,产品描述2,产品描述3,产品描述4,产品描述5');
$table->PO->range('admin,user1,user2,admin,user1');
$table->gen(5);

$programTable = zenData('project');
$programTable->id->range('1-3');
$programTable->name->range('项目集1,项目集2,项目集3');
$programTable->type->range('program');
$programTable->status->range('wait');
$programTable->gen(3);

$moduleTable = zenData('module');
$moduleTable->id->range('1-3');
$moduleTable->root->range('1,2,3');
$moduleTable->name->range('产品线1,产品线2,产品线3');
$moduleTable->type->range('line');
$moduleTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildBatchEditFormTest(0, array(1))) && p('products,programID') && e('1,0'); // 步骤1：正常情况，项目集为0，单个产品
r($productTest->buildBatchEditFormTest(1, array(1, 2, 3))) && p('products,programID') && e('3,1'); // 步骤2：正常情况，项目集为1，多个产品  
r($productTest->buildBatchEditFormTest(0, array())) && p('products') && e('0'); // 步骤3：边界值，空产品ID列表
r($productTest->buildBatchEditFormTest(0, array(999))) && p('products') && e('0'); // 步骤4：边界值，无效产品ID列表
r($productTest->buildBatchEditFormTest(1, array(1, 2))) && p('authPrograms,unauthPrograms') && e('0,1'); // 步骤5：验证项目集数据结构