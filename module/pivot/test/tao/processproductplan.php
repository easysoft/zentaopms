#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::processProductPlan();
timeout=0
cid=17452

- 执行plans) ? count($products1[1]模块的plans) : 0方法  @5
- 执行$products2[1]->plans[3]) && strpos($products2[1]->plans[3]->title, '>>') === 0 ? 1 : 0 @1
- 执行plans) ? count($products3[2]模块的plans) : 0方法  @2
- 执行plans) ? count($products4[2]模块的plans) : 0方法  @3
- 执行$products5 @0
- 执行plans) ? count($products6[5]模块的plans) : 0方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/* 生成产品数据 */
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->code->range('PROD001,PROD002,PROD003,PROD004,PROD005');
$productTable->gen(5);

/* 生成产品计划数据 */
$planTable = zenData('productplan');
$planTable->id->range('1-10');
$planTable->product->range('1,1,1,1,1,2,2,2,3,5');
$planTable->parent->range('0,0,2,0,2,0,0,0,0,0');
$planTable->title->range('版本1.0计划,版本1.1计划,子计划A,版本2.0计划,子计划B,热修复计划,功能优化计划,性能提升计划,安全增强计划,测试计划');
$planTable->begin->range('`2024-01-01`');
$planTable->end->range('`2026-12-31`,`2026-11-30`,`2026-10-31`,`2026-09-30`,`2026-08-31`,`2026-06-30`,`2024-10-31`,`2026-05-31`,`2026-04-30`,`2026-03-31`');
$planTable->gen(10);

su('admin');

$pivotTest = new pivotTaoTest();

/* 测试场景1: 正常情况 - 产品1有未过期计划 */
$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品A';
$products1 = array(1 => $product1);
$pivotTest->processProductPlanTest($products1, '');
r(isset($products1[1]->plans) ? count($products1[1]->plans) : 0) && p() && e('5');

/* 测试场景2: 父子计划关系 - 检查子计划标题是否有'>>'前缀 */
$product2 = new stdClass();
$product2->id = 1;
$product2->name = '产品A';
$products2 = array(1 => $product2);
$pivotTest->processProductPlanTest($products2, '');
r(isset($products2[1]->plans[3]) && strpos($products2[1]->plans[3]->title, '>>') === 0 ? 1 : 0) && p() && e('1');

/* 测试场景3: 过滤过期计划 - 不包含overduePlan条件 */
$product3 = new stdClass();
$product3->id = 2;
$product3->name = '产品B';
$products3 = array(2 => $product3);
$pivotTest->processProductPlanTest($products3, '');
r(isset($products3[2]->plans) ? count($products3[2]->plans) : 0) && p() && e('2');

/* 测试场景4: 包含过期计划 - conditions包含overduePlan */
$product4 = new stdClass();
$product4->id = 2;
$product4->name = '产品B';
$products4 = array(2 => $product4);
$pivotTest->processProductPlanTest($products4, 'overduePlan');
r(isset($products4[2]->plans) ? count($products4[2]->plans) : 0) && p() && e('3');

/* 测试场景5: 空产品列表 */
$products5 = array();
$pivotTest->processProductPlanTest($products5, '');
r(count($products5)) && p() && e('0');

/* 测试场景6: 产品5有1个计划 */
$product6 = new stdClass();
$product6->id = 5;
$product6->name = '产品E';
$products6 = array(5 => $product6);
$pivotTest->processProductPlanTest($products6, '');
r(isset($products6[5]->plans) ? count($products6[5]->plans) : 0) && p() && e('1');