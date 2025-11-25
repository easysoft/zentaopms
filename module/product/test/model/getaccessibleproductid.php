#!/usr/bin/env php
<?php

/**

title=测试 productModel::getAccessibleProductID;
timeout=0
cid=17485

- 不传入ID @1
- 传入存在ID的值 @1
- 传入存在ID的值 @2
- 传入存在ID的值 @3
- 传入存在ID的值 @10
- 传入不存在ID的值 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(10);
su('admin');

global $tester;
$tester->loadModel('product');

$products = array(1 => 1, 2 => 2, 6 => 6);
r($tester->product->getAccessibleProductID(0,  $products)) && p() && e('1');  //不传入ID
r($tester->product->getAccessibleProductID(1,  $products)) && p() && e('1');  //传入存在ID的值
r($tester->product->getAccessibleProductID(2,  $products)) && p() && e('2');  //传入存在ID的值
r($tester->product->getAccessibleProductID(3,  $products)) && p() && e('3');  //传入存在ID的值
r($tester->product->getAccessibleProductID(10, $products)) && p() && e('10'); //传入存在ID的值
r($tester->product->getAccessibleProductID(11, $products)) && p() && e('1');  //传入不存在ID的值
