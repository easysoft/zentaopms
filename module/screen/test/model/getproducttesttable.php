#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getProductTestTable();
timeout=0
cid=18252

- 步骤1：正常情况测试
 - 第0条的name属性 @产品A
 - 第0条的createdCases属性 @3
- 步骤2：空产品列表 @0
- 步骤3：无效年份 @0
- 步骤4：单产品计算第0条的avgBugsOfCase属性 @6.67
- 步骤5：无效月份 @0
- 步骤6：多产品聚合 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,测试产品,Demo产品');
$product->deleted->range('0{5}');
$product->gen(5);

$case = zenData('case');
$case->id->range('1-15');
$case->product->range('1{3},2{3},3{3},4{3},5{3}');
$case->deleted->range('0{15}');
$case->openedDate->range('`2024-01-15 10:00:00`,`2024-01-20 11:00:00`,`2024-01-25 12:00:00`');
$case->gen(15);

$bug = zenData('bug');
$bug->id->range('1-20');
$bug->product->range('1{4},2{4},3{4},4{4},5{4}');
$bug->case->range('1-15{15},0{5}');
$bug->deleted->range('0{20}');
$bug->openedDate->range('`2024-01-16 10:00:00`,`2024-01-21 11:00:00`,`2024-01-26 12:00:00`');
$bug->closedDate->range('`2024-01-18 10:00:00`,`2024-01-23 11:00:00`,`2024-01-28 12:00:00`');
$bug->status->range('active{8},resolved{6},closed{6}');
$bug->resolution->range('fixed{10},duplicate{5},postponed{5}');
$bug->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$productList = array(1 => '产品A', 2 => '产品B', 3 => '产品C');
r($screenTest->getProductTestTableTest('2024', '01', $productList)) && p('0:name,createdCases') && e('产品A,3'); // 步骤1：正常情况测试
r($screenTest->getProductTestTableTest('2024', '01', array())) && p() && e('0'); // 步骤2：空产品列表
r($screenTest->getProductTestTableTest('invalid', '01', $productList)) && p() && e('0'); // 步骤3：无效年份
r($screenTest->getProductTestTableTest('2024', '01', array(1 => '产品A'))) && p('0:avgBugsOfCase') && e('6.67'); // 步骤4：单产品计算
r($screenTest->getProductTestTableTest('2024', '13', $productList)) && p() && e('0'); // 步骤5：无效月份  
r($screenTest->getProductTestTableTest('2024', '01', array(1 => '产品A', 2 => '产品B', 3 => '产品C'))) && p() && e('Array'); // 步骤6：多产品聚合