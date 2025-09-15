#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::processProductPlan();
timeout=0
cid=0

- 步骤1：正常情况属性count() @0
- 步骤2：空产品数组属性count() @0
- 步骤3：父子计划关系第1条的id属性 @8
- 步骤4：过期计划过滤第8条的id属性 @3
- 步骤5：多产品场景第6条的product属性 @0
- 步骤6：无计划产品 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->code->range('product1,product2,product3,product4,product5');
$productTable->PO->range('admin,user1,user2,admin,user1');
$productTable->deleted->range('0');
$productTable->shadow->range('0');
$productTable->gen(5);

$planTable = zenData('productplan');
$planTable->id->range('1-10');
$planTable->product->range('1{2},2{3},3{2},4{1},5{2}');
$planTable->parent->range('0{6},1{1},2{1},5{1},8{1}');
$planTable->title->range('计划1.1,计划1.2,计划2.1,计划2.2,计划2.3,计划3.1,子计划1.1,子计划2.1,计划3.2,子计划3.1');
$planTable->begin->range('20240101,20240201,20240301,20240401,20240501,20240601,20240701,20240801,20240901,20241001');
$planTable->end->range('20250630,20250731,20250831,20250930,20251031,20251130,20251231,20260131,20260228,20260331');
$planTable->deleted->range('0');
$planTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 测试包含有效产品的产品计划处理
$products1 = array();
$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品1';
$products1[1] = $product1;
$product2 = new stdClass();
$product2->id = 2;
$product2->name = '产品2';
$products1[2] = $product2;
r($pivotTest->processProductPlanTest($products1, '')) && p('count()') && e('0'); // 步骤1：正常情况

// 步骤2：空产品数组 - 测试空产品数组情况
$products2 = array();
r($pivotTest->processProductPlanTest($products2, '')) && p('count()') && e('0'); // 步骤2：空产品数组

// 步骤3：父子计划关系 - 测试包含父子计划关系的产品
$products3 = array();
$product3 = new stdClass();
$product3->id = 1;
$product3->name = '产品1';
$products3[1] = $product3;
r($pivotTest->processProductPlanTest($products3, '')) && p('1:id') && e('8'); // 步骤3：父子计划关系

// 步骤4：过期计划过滤 - 测试不同conditions参数对过期计划的过滤
$products4 = array();
$product4 = new stdClass();
$product4->id = 4;
$product4->name = '产品4';
$products4[4] = $product4;
r($pivotTest->processProductPlanTest($products4, 'overduePlan')) && p('8:id') && e('3'); // 步骤4：过期计划过滤

// 步骤5：多产品场景 - 测试多个产品的计划处理
$products5 = array();
$product5a = new stdClass();
$product5a->id = 3;
$product5a->name = '产品3';
$products5[3] = $product5a;
$product5b = new stdClass();
$product5b->id = 5;
$product5b->name = '产品5';
$products5[5] = $product5b;
r($pivotTest->processProductPlanTest($products5, '')) && p('6:product') && e('0'); // 步骤5：多产品场景

// 步骤6：无计划产品 - 测试某些产品没有计划的情况
$products6 = array();
$product6 = new stdClass();
$product6->id = 999;
$product6->name = '无计划产品';
$products6[999] = $product6;
r($pivotTest->processProductPlanTest($products6, '')) && p() && e('~~'); // 步骤6：无计划产品