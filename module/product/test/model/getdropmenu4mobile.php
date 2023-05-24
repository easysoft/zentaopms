#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(10);

/**

title=测试productModel->getDropMenu4Mobile();
cid=1
pid=1

*/

global $tester;
$productTest = $tester->loadModel('product');
$productTest->app->viewType = 'mhtml';
$products = $productTest->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchPairs('id', 'name');

r(strpos($productTest->getDropMenu4Mobile($products, -1), 'showSearchMenu') !== false) && p() && e('1');
r(strpos($productTest->getDropMenu4Mobile($products, 0),  'showSearchMenu') !== false) && p() && e('1');
r(strpos($productTest->getDropMenu4Mobile($products, 1),  'showSearchMenu') !== false) && p() && e('1');
r(strpos($productTest->getDropMenu4Mobile(array(), -1),   'showSearchMenu') !== false) && p() && e('0');
r(strpos($productTest->getDropMenu4Mobile(array(), 0),    'showSearchMenu') !== false) && p() && e('0');
r(strpos($productTest->getDropMenu4Mobile(array(), 1),    'showSearchMenu') !== false) && p() && e('0');
