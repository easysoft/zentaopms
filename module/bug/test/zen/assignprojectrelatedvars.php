#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignProjectRelatedVars();
timeout=0
cid=0

- 步骤1：空Bug数组输入情况 @0
- 步骤2：正常Bug数组，正常产品数组 @2
- 步骤3：混合Bug数组，影子产品数组 @3
- 步骤4：正常Bug数组，混合产品数组 @2
- 步骤5：包含无项目和无执行的Bug数组 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（简化版本，减少数据生成）
$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->product->range('1-3');
$bugTable->project->range('0{5},1{3},2{2}');
$bugTable->execution->range('0{5},101{3},102{2}');
$bugTable->branch->range('0{8},1{2}');
$bugTable->title->range('Bug{1-10}');
$bugTable->status->range('active');
$bugTable->openedBy->range('admin');
$bugTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('产品1,产品2,产品3');
$productTable->shadow->range('0');
$productTable->deleted->range('0');
$productTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->assignProjectRelatedVarsTest('empty', 'normal')) && p() && e(0); // 步骤1：空Bug数组输入情况
r($bugTest->assignProjectRelatedVarsTest('normal', 'normal')) && p() && e(2); // 步骤2：正常Bug数组，正常产品数组
r($bugTest->assignProjectRelatedVarsTest('mixed', 'shadow')) && p() && e(3); // 步骤3：混合Bug数组，影子产品数组
r($bugTest->assignProjectRelatedVarsTest('normal', 'mixed')) && p() && e(2); // 步骤4：正常Bug数组，混合产品数组
r($bugTest->assignProjectRelatedVarsTest('mixed', 'normal')) && p() && e(3); // 步骤5：包含无项目和无执行的Bug数组