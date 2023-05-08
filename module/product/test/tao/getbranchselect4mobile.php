#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

zdTable('product')->gen(50);
$branch = zdTable('branch');
$branch->product->range('41');
$branch->gen(5);

/**

title=测试productModel->getBranchSelect4Mobile();
timeout=0
cid=1

*/

global $tester;
$productTest = $tester->loadModel('product');
$product = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq(1)->fetch();

$moduleName = 'product';
$methodName = 'browse';
r($productTest->getBranchSelect4Mobile(new stdclass(), '', $moduleName, $methodName)) && p() && e('0');
r($productTest->getBranchSelect4Mobile($product,       '', $moduleName, $methodName)) && p() && e('0');

$product = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq(41)->fetch();
$result  = $productTest->getBranchSelect4Mobile($product, 'all', $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '所有') !== false)) && p() && e('1');

$result = $productTest->getBranchSelect4Mobile($product, 1,  $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '分支1') !== false)) && p() && e('1');
