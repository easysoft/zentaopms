#!/usr/bin/env php
<?php

/**

title=测试 productZen::getBranchID();
timeout=0
cid=0

- 步骤1：空产品对象 @all
- 步骤2：普通产品类型 @all
- 步骤3：指定分支 @main
- 步骤4：空分支且无预分支时返回空分支 @0
- 步骤5：不存在的产品返回空字符串 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备
$table = zenData('branch');
$table->id->range('1-5');
$table->product->range('2,2,3,3,3');
$table->name->range('main,dev,branch1,branch2,branch3');
$table->status->range('active{4},closed{1}');
$table->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('普通产品,分支产品1,分支产品2');
$productTable->type->range('normal,branch,branch');
$productTable->status->range('normal{3}');
$productTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$productTest = new productTest();

// 5. 测试步骤
r($productTest->getBranchIDTest(null, '')) && p() && e('all'); // 步骤1：空产品对象
r($productTest->getBranchIDTest((object)array('id' => 1, 'type' => 'normal'), '')) && p() && e('all'); // 步骤2：普通产品类型
r($productTest->getBranchIDTest((object)array('id' => 2, 'type' => 'branch'), 'main')) && p() && e('main'); // 步骤3：指定分支
r($productTest->getBranchIDTest((object)array('id' => 2, 'type' => 'branch'), '')) && p() && e('0'); // 步骤4：空分支且无预分支时返回空分支
r($productTest->getBranchIDTest((object)array('id' => 999, 'type' => 'branch'), '')) && p() && e('0'); // 步骤5：不存在的产品返回空字符串