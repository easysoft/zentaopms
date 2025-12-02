#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=executionModel->getBranchByProduct();
timeout=0
cid=16298

- 正常产品分支统计 @0
- 正常产品分支统计 @0
- 分支产品分支查看第2条的1属性 @分支1
- 分支产品分支统计 @3
- 分支产品分支查看第2条的2属性 @分支2

*/

zenData('user')->gen(5);
su('admin');

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('正常产品1,多分支产品1');
$product->type->range('normal,branch');
$product->status->range('normal');
$product->createdBy->range('admin,user1');
$product->gen(2);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('2');
$branch->name->range('分支1,分支2');
$branch->status->range('active');
$branch->gen(2);

$productIDList = array(1, 2);
$count         = array(0, 1);

$executionTester = new executionTest();
r($executionTester->getBranchByProductTest($productIDList[0], $count[0])) && p()      && e('0');     // 正常产品分支统计
r($executionTester->getBranchByProductTest($productIDList[0], $count[1])) && p()      && e('0');     // 正常产品分支统计
r($executionTester->getBranchByProductTest($productIDList[1], $count[0])) && p('2:1') && e('分支1'); // 分支产品分支查看
r($executionTester->getBranchByProductTest($productIDList[1], $count[1])) && p()      && e('3');     // 分支产品分支统计
r($executionTester->getBranchByProductTest($productIDList[1], $count[0])) && p('2:2') && e('分支2'); // 分支产品分支查看
