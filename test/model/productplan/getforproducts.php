#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$products = array();
$products[0] = array(1, 2);
$products[1] = array(1000,1001);

r($plan->getForProducts($products[0])) && p() && e('3'); //测试传入一个数组，取出产品名称count
r($plan->getForProducts($products[1])) && p() && e('0'); //测试传入一个不存在的product数组,应为空
?>
