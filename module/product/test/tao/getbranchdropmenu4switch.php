#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->gen(50);
$branch = zdTable('branch');
$branch->product->range('41');
$branch->gen(5);

/**

title=测试productModel->getBranchDropMenu4Switch();
timeout=0
cid=1

*/

global $tester;
$productTest = $tester->loadModel('product');
$product = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq(1)->fetch();

$moduleName = 'product';
$methodName = 'browse';
r($productTest->getBranchDropMenu4Switch(new stdclass(), '', $moduleName, $methodName)) && p() && e('0');
r($productTest->getBranchDropMenu4Switch($product,       '', $moduleName, $methodName)) && p() && e('0');

$product = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq(41)->fetch();
$result  = $productTest->getBranchDropMenu4Switch($product, '', $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '所有') !== false)) && p() && e('1');

$result = $productTest->getBranchDropMenu4Switch($product, 1,  $moduleName, $methodName);
r((strpos($result, 'currentBranch') !== false and strpos($result, '分支1') !== false)) && p() && e('1');

r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'testcase', 'showimport'), 'currentBranch') !== false))  && p() && e('0');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'tree',     'browse'), 'currentBranch') !== false))      && p() && e('1');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'product',  'view'), 'currentBranch') !== false))        && p() && e('0');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'release',  'create'), 'currentBranch') !== false))      && p() && e('1');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'release',  'view'), 'currentBranch') !== false))        && p() && e('0');

$productTest->app->tab = 'qa';
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'bug',      'view'), 'currentBranch') !== false))   && p() && e('1');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'testcase', 'view'), 'currentBranch') !== false))   && p() && e('1');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'testtask', 'view'), 'currentBranch') !== false))   && p() && e('1');
r((strpos($productTest->getBranchDropMenu4Switch($product, 1,  'testtask', 'create'), 'currentBranch') !== false)) && p() && e('0');
