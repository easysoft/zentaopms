#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getRoadmap();
cid=1
pid=1

测试获取产品1的roadmap数量 >> 5
测试获取产品2的roadmap数量 >> 2
测试获取产品3的roadmap数量 >> 0
测试获取产品4的roadmap数量 >> 0
测试获取产品5的roadmap数量 >> 0
测试获取不存在产品的roadmap数量 >> 0

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getRoadmapTest($productIDList[0])) && p('total') && e('5');   // 测试获取产品1的roadmap数量
r($product->getRoadmapTest($productIDList[1])) && p('total') && e('2');   // 测试获取产品2的roadmap数量
r($product->getRoadmapTest($productIDList[2])) && p('total') && e('0');   // 测试获取产品3的roadmap数量
r($product->getRoadmapTest($productIDList[3])) && p('total') && e('0');   // 测试获取产品4的roadmap数量
r($product->getRoadmapTest($productIDList[4])) && p('total') && e('0');   // 测试获取产品5的roadmap数量
r($product->getRoadmapTest($productIDList[5])) && p('total') && e('0');   // 测试获取不存在产品的roadmap数量
