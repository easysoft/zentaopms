#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

/**

title=测试productModel->getMobileDropMenu();
cid=1
pid=1

测试admin能否看到产品1 >> 1
测试admin能否看到产品2 >> 1
测试admin能否看到产品3 >> 1
测试admin能否看到产品4 >> 1
测试admin能否看到产品5 >> 1
测试admin能否看到不存在的产品 >> 1
测试po1能否看到产品1 >> 1
测试po1能否看到产品2 >> 1
测试po1能否看到产品3 >> 1
测试po1能否看到产品4 >> 1
测试po1能否看到产品5 >> 1
测试po1能否看到不存在的产品 >> 2

*/

global $tester;
$productTest = $tester->loadModel('product');
$products = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchPairs('id', 'name');

r(strpos($productTest->getMobileDropMenu($products, -1), 'showSearchMenu') !== false) && p() && e('1');
r(strpos($productTest->getMobileDropMenu($products, 0),  'showSearchMenu') !== false) && p() && e('1');
r(strpos($productTest->getMobileDropMenu($products, 1),  'showSearchMenu') !== false) && p() && e('1');
r(strpos($productTest->getMobileDropMenu(array(), -1),   'showSearchMenu') !== false) && p() && e('0');
r(strpos($productTest->getMobileDropMenu(array(), 0),    'showSearchMenu') !== false) && p() && e('0');
r(strpos($productTest->getMobileDropMenu(array(), 1),    'showSearchMenu') !== false) && p() && e('0');
