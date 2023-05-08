#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

zdTable('product')->gen(50);
$branch = zdTable('branch');
$branch->product->range('41');
$branch->gen(5);

/**

title=测试productModel->getBranchDropMenu4Select();
timeout=0
cid=1

*/

global $tester;
$productTest = $tester->loadModel('product');
$product = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq(1)->fetch();

$moduleName = 'product';
$methodName = 'browse';
r($productTest->getBranchDropMenu4Select(new stdclass(), '', $moduleName, $methodName))            && p() && e('0');
r($productTest->getBranchDropMenu4Select($product,       '', $moduleName, $methodName))            && p() && e('0');
r($productTest->getBranchDropMenu4Select($product,       '', $moduleName, $methodName, '', false)) && p() && e('0');

$productTest->lang->product->menu->settings['subMenu']->branch = array('link' => "@branch@|branch|manage|product=%s", 'subModule' => 'branch');
$product = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq(41)->fetch();
$result  = $productTest->getBranchDropMenu4Select($product, 'all', $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '所有') !== false)) && p() && e('1');

$result = $productTest->getBranchDropMenu4Select($product, 1,  $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '分支1') !== false)) && p() && e('1');

$productTest->app->viewType = 'mhtml';
$result  = $productTest->getBranchDropMenu4Select($product, 'all', $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '所有') !== false)) && p() && e('1');

$result = $productTest->getBranchDropMenu4Select($product, 1,  $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '分支1') !== false)) && p() && e('1');
