#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignProductAndBranchForBrowse();
timeout=0
cid=0

- 步骤1：普通产品类型
 - 属性productID @1
 - 属性productName @普通产品
 - 属性branch @0
- 步骤2：分支产品类型
 - 属性productID @2
 - 属性productName @分支产品A
 - 属性branch @main
- 步骤3：不存在的产品ID属性productID @999
- 步骤4：空的产品ID属性productID @0
- 步骤5：带项目ID的分支产品
 - 属性productID @3
 - 属性productName @分支产品B
 - 属性branch @dev

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('普通产品,分支产品A,分支产品B,正常产品,测试产品');
$productTable->type->range('normal,branch,branch,normal,normal');
$productTable->status->range('normal{5}');
$productTable->deleted->range('0{5}');
$productTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-6');
$branchTable->product->range('2{3},3{3}');
$branchTable->name->range('主分支,开发分支,发布分支,分支1,分支2,分支3');
$branchTable->status->range('active{4},closed{2}');
$branchTable->deleted->range('0{6}');
$branchTable->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignProductAndBranchForBrowseTest(1, '', 0)) && p('productID,productName,branch') && e('1,普通产品,0'); // 步骤1：普通产品类型
r($testcaseTest->assignProductAndBranchForBrowseTest(2, 'main', 0)) && p('productID,productName,branch') && e('2,分支产品A,main'); // 步骤2：分支产品类型
r($testcaseTest->assignProductAndBranchForBrowseTest(999, '', 0)) && p('productID') && e('999'); // 步骤3：不存在的产品ID
r($testcaseTest->assignProductAndBranchForBrowseTest(0, '', 0)) && p('productID') && e('0'); // 步骤4：空的产品ID
r($testcaseTest->assignProductAndBranchForBrowseTest(3, 'dev', 100)) && p('productID,productName,branch') && e('3,分支产品B,dev'); // 步骤5：带项目ID的分支产品