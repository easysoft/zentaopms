#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->getForProducts();
cid=1
pid=1

测试传入一个数组，取出产品名称count >> 1.0
测试传入一个不存在的product数组,应为空 >> 6

*/

$plan = new productPlan('admin');

$products = array();
$products[0] = array(1, 2);
$products[1] = array(1000,1001);

$noProduct = count($plan->getForProducts(array(1000,1001))) -1;

r($plan->getForProducts($products[0])) && p('1') && e('1.0'); //测试传入一个数组，取出产品名称count
r($noProduct)                          && p()    && e('6'); //测试传入一个不存在的product数组,应为空
?>
