#!/usr/bin/env php
<?php

/**

title=测试 productModel::checkAccess;
timeout=0
cid=17476

- 不传入ID @1
- 传入存在ID的值 @2
- 传入存在ID的值 @6
- 不传入ID，读取session信息 @6
- 传入正确的ID @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(10);
su('admin');

global $tester;
$tester->loadModel('product');

$products = array(1 => 1, 2 => 2, 6 => 6);
r($tester->product->checkAccess(0,  $products)) && p() && e('1');  //不传入ID
r($tester->product->checkAccess(2,  $products)) && p() && e('2');  //传入存在ID的值
r($tester->product->checkAccess(6,  $products)) && p() && e('6');  //传入存在ID的值
r($tester->product->checkAccess(0,  $products)) && p() && e('6');  //不传入ID，读取session信息
r($tester->product->checkAccess(10, $products)) && p() && e('10'); //传入正确的ID
