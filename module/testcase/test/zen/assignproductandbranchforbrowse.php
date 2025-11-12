#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignProductAndBranchForBrowse();
timeout=0
cid=0

- 步骤1:测试普通产品(不显示分支)
 - 属性productID @1
 - 属性productType @normal
 - 属性branch @0
 - 属性branchOptionCount @0
- 步骤2:测试分支产品(显示分支),不传projectID
 - 属性productID @2
 - 属性productType @branch
 - 属性branchOptionCount @6
- 步骤3:测试分支产品,传入projectID
 - 属性productID @3
 - 属性productType @branch
 - 属性branchOptionCount @1
- 步骤4:测试平台产品
 - 属性productID @4
 - 属性productType @platform
 - 属性branchOptionCount @3
- 步骤5:测试不存在的产品ID
 - 属性productID @999
 - 属性branchOptionCount @0
- 步骤6:测试分支产品指定分支ID
 - 属性productID @2
 - 属性branch @1
 - 属性branchOptionCount @6
- 步骤7:测试分支产品的分支标签选项
 - 属性productID @3
 - 属性branchOptionCount @4
 - 属性branchTagOptionCount @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('普通产品,分支产品A,分支产品B,平台产品,产品E');
$productTable->type->range('normal,branch{2},platform,normal');
$productTable->status->range('normal');
$productTable->createdBy->range('admin');
$productTable->createdDate->range('`2024-01-01 10:00:00`');
$productTable->deleted->range('0');
$productTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-10');
$branchTable->product->range('2{5},3{3},4{2}');
$branchTable->name->range('分支A-1,分支A-2,分支A-3,分支A-4,分支A-5,分支B-1,分支B-2,分支B-3,平台分支1,平台分支2');
$branchTable->status->range('active{7},closed{3}');
$branchTable->default->range('1,0{9}');
$branchTable->deleted->range('0');
$branchTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('项目A,项目B,项目C');
$projectTable->type->range('project');
$projectTable->status->range('doing');
$projectTable->openedBy->range('admin');
$projectTable->openedDate->range('`2024-01-01 10:00:00`');
$projectTable->deleted->range('0');
$projectTable->gen(3);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1-3');
$projectProductTable->product->range('2,3,4');
$projectProductTable->branch->range('1,4,8');
$projectProductTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignProductAndBranchForBrowseTest(1, '0', 0)) && p('productID,productType,branch,branchOptionCount') && e('1,normal,0,0'); // 步骤1:测试普通产品(不显示分支)
r($testcaseTest->assignProductAndBranchForBrowseTest(2, '0', 0)) && p('productID,productType,branchOptionCount') && e('2,branch,6'); // 步骤2:测试分支产品(显示分支),不传projectID
r($testcaseTest->assignProductAndBranchForBrowseTest(3, '0', 2)) && p('productID,productType,branchOptionCount') && e('3,branch,1'); // 步骤3:测试分支产品,传入projectID
r($testcaseTest->assignProductAndBranchForBrowseTest(4, '0', 0)) && p('productID,productType,branchOptionCount') && e('4,platform,3'); // 步骤4:测试平台产品
r($testcaseTest->assignProductAndBranchForBrowseTest(999, '0', 0)) && p('productID,branchOptionCount') && e('999,0'); // 步骤5:测试不存在的产品ID
r($testcaseTest->assignProductAndBranchForBrowseTest(2, '1', 0)) && p('productID,branch,branchOptionCount') && e('2,1,6'); // 步骤6:测试分支产品指定分支ID
r($testcaseTest->assignProductAndBranchForBrowseTest(3, '0', 0)) && p('productID,branchOptionCount,branchTagOptionCount') && e('3,4,4'); // 步骤7:测试分支产品的分支标签选项