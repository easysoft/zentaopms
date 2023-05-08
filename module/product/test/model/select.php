#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
$branch = zdTable('branch');
$branch->product->range('41');
$branch->gen(5);

/**

title=测试productModel->select();
timeout=0
cid=1

*/

$productTest = new productTest('admin');
$productTest->objectModel->dao->update(TABLE_PRODUCT)->set('deleted')->eq(1)->where('id')->eq(49)->exec();
$products = $productTest->objectModel->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchPairs('id', 'name');

$moduleName = 'product';
$methodName = 'browse';
r($productTest->selectTest(array(),   0,  $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('0,0');
r($productTest->selectTest($products, 0,  $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 1,  $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 49, $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 41, $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,1');
r($productTest->selectTest($products, 41, $moduleName, $methodName, '', 1))         && p('hasProduct,hasBranch') && e('1,1');
r($productTest->selectTest($products, 41, $moduleName, $methodName, '', '', false)) && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 70, $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');

$productTest->objectModel->app->tab = 'project';
$moduleName = 'project';
$methodName = 'testcase';
r($productTest->selectTest($products, 41,  $moduleName, $methodName)) && p('hasProduct,hasBranch') && e('1,1');
$moduleName = 'testcase';
$methodName = 'groupCase';
r($productTest->selectTest($products, 41,  $moduleName, $methodName)) && p('hasProduct,hasBranch') && e('1,1');

$productTest->objectModel->app->tab = 'execution';
$moduleName = 'execution';
$methodName = 'bug';
r($productTest->selectTest($products, 41,  $moduleName, $methodName)) && p('hasProduct,hasBranch') && e('1,1');
$methodName = 'task';
r($productTest->selectTest($products, 41,  $moduleName, $methodName)) && p('hasProduct,hasBranch') && e('1,1');

$productTest->objectModel->app->tab = 'feedback';
$moduleName = 'bug';
$methodName = 'view';
r($productTest->selectTest($products, 41,  $moduleName, $methodName)) && p('hasProduct,hasBranch') && e('1,1');

$productTest->objectModel->app->viewType = 'mhtml';
r($productTest->selectTest(array(),   0,  $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('0,0');
r($productTest->selectTest($products, 0,  $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 1,  $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 49, $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 41, $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,1');
r($productTest->selectTest($products, 41, $moduleName, $methodName, '', 1))         && p('hasProduct,hasBranch') && e('1,1');
r($productTest->selectTest($products, 41, $moduleName, $methodName, '', '', false)) && p('hasProduct,hasBranch') && e('1,0');
r($productTest->selectTest($products, 70, $moduleName, $methodName))                && p('hasProduct,hasBranch') && e('1,0');
