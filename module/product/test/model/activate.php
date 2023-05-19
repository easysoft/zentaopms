#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->config('product')->gen(10);

/**
title=测试productModel->activate();
cid=1
pid=1

*/

$product = $tester->loadModel('product');

$normalProductIdList = array(1, 2, 3, 4, 5);
$closedProductIdList = array(6, 7, 8, 9, 10);
$wrongProductIdList  = array(11, 0, -1);

r($product->activate($normalProductIdList[0])) && p() && e('0'); // 测试未关闭产品1
r($product->activate($normalProductIdList[1])) && p() && e('0'); // 测试未关闭产品2
r($product->activate($normalProductIdList[2])) && p() && e('0'); // 测试未关闭产品3
r($product->activate($normalProductIdList[3])) && p() && e('0'); // 测试未关闭产品4
r($product->activate($normalProductIdList[4])) && p() && e('0'); // 测试未关闭产品5

r($product->activate($closedProductIdList[0])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品1
r($product->activate($closedProductIdList[1])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品2
r($product->activate($closedProductIdList[2])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品3
r($product->activate($closedProductIdList[3])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品4
r($product->activate($closedProductIdList[4])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品5

r($product->activate($wrongProductIdList[0]))  && p(0) && e('0'); // 测试不存在的产品11
r($product->activate($wrongProductIdList[1]))  && p(0) && e('0'); // 测试不存在的产品0
r($product->activate($wrongProductIdList[2]))  && p(0) && e('0'); // 测试不存在的产品-1
