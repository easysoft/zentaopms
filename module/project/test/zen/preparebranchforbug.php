#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareBranchForBug();
timeout=0
cid=0

- 步骤1：正常产品数组输入情况 @22
- 步骤2：空产品数组输入 @0
- 步骤3：产品ID过滤情况 @8
- 步骤4：多分支产品处理情况 @8
- 步骤5：已关闭分支状态显示 @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{2},branch{3}');
$productTable->status->range('normal');
$productTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-10');
$branchTable->product->range('3{3},4{3},5{4}');
$branchTable->name->range('主干,分支1,分支2,分支3,分支4,分支5,分支6,分支7,分支8,分支9');
$branchTable->status->range('active{8},closed{2}');
$branchTable->deleted->range('0');
$branchTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 准备测试数据
$products = array();
for($i = 1; $i <= 5; $i++)
{
    $product = new stdClass();
    $product->id = $i;
    $product->name = "产品" . chr(64 + $i);
    $product->type = $i <= 2 ? 'normal' : 'branch';
    $products[$i] = $product;
}

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectzenTest->prepareBranchForBugTest($products, 0)) && p() && e('22'); // 步骤1：正常产品数组输入情况
r($projectzenTest->prepareBranchForBugTest(array(), 0)) && p() && e('0'); // 步骤2：空产品数组输入
r($projectzenTest->prepareBranchForBugTest($products, 3)) && p() && e('8'); // 步骤3：产品ID过滤情况
r($projectzenTest->prepareBranchForBugTest($products, 4)) && p() && e('8'); // 步骤4：多分支产品处理情况
r($projectzenTest->prepareBranchForBugTest($products, 5)) && p() && e('10'); // 步骤5：已关闭分支状态显示