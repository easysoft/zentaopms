#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::processProductPlan();
timeout=0
cid=0

- 步骤1：正常情况下处理产品计划属性count() @2
- 步骤2：空产品数组的处理属性count() @0
- 步骤3：父子计划关系的处理第7条的parent属性 @1
- 步骤4：过期计划的过滤功能属性count() @1
- 步骤5：多产品计划的处理属性count() @4
- 步骤6：无计划产品的处理属性count() @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('company')->gen(1);
zenData('user')->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->code->range('product1,product2,product3,product4,product5');
$productTable->PO->range('admin,user1,user2,admin,user1');
$productTable->deleted->range('0');
$productTable->shadow->range('0');
$productTable->status->range('normal');
$productTable->type->range('normal');
$productTable->gen(5);

$planTable = zenData('productplan');
$planTable->id->range('1-10');
$planTable->product->range('1{2},2{3},3{2},4{1},5{2}');
$planTable->parent->range('0{6},1{1},2{1},5{1},8{1}');
$planTable->title->range('计划1.1,计划1.2,计划2.1,计划2.2,计划2.3,计划3.1,子计划1.1,子计划2.1,计划3.2,子计划3.1');
$planTable->begin->range('2024-01-01,2024-02-01,2024-03-01,2024-04-01,2024-05-01,2024-06-01,2024-07-01,2024-08-01,2024-09-01,2024-10-01');
$planTable->end->range('2026-06-30,2026-07-31,2026-08-31,2026-09-30,2026-10-31,2026-11-30,2026-12-31,2027-01-31,2027-02-28,2027-03-31');
$planTable->deleted->range('0');
$planTable->branch->range('0');
$planTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况下处理产品计划 - 测试有计划的产品处理
$products1 = array();
$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品1';
$products1[1] = $product1;
r($pivotTest->processProductPlanTest($products1, '')) && p('count()') && e('2'); // 步骤1：正常情况下处理产品计划

// 步骤2：空产品数组的处理 - 测试空产品数组情况
$products2 = array();
r($pivotTest->processProductPlanTest($products2, '')) && p('count()') && e('0'); // 步骤2：空产品数组的处理

// 步骤3：父子计划关系的处理 - 测试包含父子计划关系的产品
$products3 = array();
$product3 = new stdClass();
$product3->id = 1;
$product3->name = '产品1';
$products3[1] = $product3;
r($pivotTest->processProductPlanTest($products3, '')) && p('7:parent') && e('1'); // 步骤3：父子计划关系的处理

// 步骤4：过期计划的过滤功能 - 测试不同conditions参数对过期计划的过滤
$products4 = array();
$product4 = new stdClass();
$product4->id = 4;
$product4->name = '产品4';
$products4[4] = $product4;
r($pivotTest->processProductPlanTest($products4, 'overduePlan')) && p('count()') && e('1'); // 步骤4：过期计划的过滤功能

// 步骤5：多产品计划的处理 - 测试多个产品的计划处理
$products5 = array();
$product5a = new stdClass();
$product5a->id = 3;
$product5a->name = '产品3';
$products5[3] = $product5a;
$product5b = new stdClass();
$product5b->id = 5;
$product5b->name = '产品5';
$products5[5] = $product5b;
r($pivotTest->processProductPlanTest($products5, '')) && p('count()') && e('4'); // 步骤5：多产品计划的处理

// 步骤6：无计划产品的处理 - 测试某些产品没有计划的情况
$products6 = array();
$product6 = new stdClass();
$product6->id = 999;
$product6->name = '无计划产品';
$products6[999] = $product6;
r($pivotTest->processProductPlanTest($products6, '')) && p('count()') && e('0'); // 步骤6：无计划产品的处理