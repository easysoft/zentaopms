#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->updateProductsTest();
cid=1
pid=1

测试修改敏捷执行关联产品 >> 2
测试修改瀑布执行关联产品 >> 82
测试修改看板执行关联产品 >> 92

*/

$executionIDList = array('101','131','251');
$productIDlist   = array('2','82','92');
$products        = array('products' => $productIDlist);

$execution = new executionTest();
r($execution->updateProductsTest($executionIDList[0], $products)) && p('0:product') && e('2');  // 测试修改敏捷执行关联产品
r($execution->updateProductsTest($executionIDList[1], $products)) && p('1:product') && e('82'); // 测试修改瀑布执行关联产品
r($execution->updateProductsTest($executionIDList[2], $products)) && p('2:product') && e('92'); // 测试修改看板执行关联产品

$db->restoreDB();