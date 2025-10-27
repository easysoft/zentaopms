#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignProductRelatedVars();
timeout=0
cid=0

- 步骤1：空数组输入情况 @0
- 步骤2：空Bug数组，正常产品类型 @0
- 步骤3：正常Bug数组，空产品数组 @0
- 步骤4：正常Bug和产品数组 @0
- 步骤5：混合类型Bug和产品数组 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_assignproductrelatedvars', false, 2)->gen(10);

$bugTable = zenData('bug');
$bugTable->loadYaml('bug_assignproductrelatedvars', false, 2)->gen(20);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_assignproductrelatedvars', false, 2)->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('1-20');
$moduleTable->name->range('模块{1-20}');
$moduleTable->type->range('bug');
$moduleTable->parent->range('0');
$moduleTable->grade->range('1');
$moduleTable->order->range('1-20');
$moduleTable->gen(20);

$planTable = zenData('productplan');
$planTable->id->range('1-10');
$planTable->product->range('1-10');
$planTable->title->range('计划{1-10}');
$planTable->status->range('wait{5},doing{3},done{2}');
$planTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->assignProductRelatedVarsTest(array(), array())) && p() && e(0); // 步骤1：空数组输入情况
r($bugTest->assignProductRelatedVarsTest(array(), 'normal')) && p() && e(0); // 步骤2：空Bug数组，正常产品类型
r($bugTest->assignProductRelatedVarsTest('normal', array())) && p() && e(0); // 步骤3：正常Bug数组，空产品数组
r($bugTest->assignProductRelatedVarsTest('normal', 'normal')) && p() && e(0); // 步骤4：正常Bug和产品数组
r($bugTest->assignProductRelatedVarsTest('mixed', 'mixed')) && p() && e(2); // 步骤5：混合类型Bug和产品数组