#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->gen(10);
su('admin');

/**

title=测试 productModel::checkAccess;
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('product');

$products = array(1 => 1, 2 => 2, 6 => 6);
$idList   = array(0, 6, 10);
r($tester->product->checkAccess($idList[0], $products)) && p() && e('1');  //不传入ID
r($tester->product->checkAccess($idList[1], $products)) && p() && e('6');  //传入存在ID的值
r($tester->product->checkAccess($idList[0], $products)) && p() && e('1');  //不传入ID，读取session信息
r($tester->product->checkAccess($idList[2], $products)) && p() && e('10'); //传入正确的ID
