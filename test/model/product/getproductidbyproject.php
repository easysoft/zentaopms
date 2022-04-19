#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getProductIDByProject();
cid=1
pid=1

测试获取项目11的产品信息 >> 1
测试获取项目12的产品信息 >> 2
测试获取项目13的产品信息 >> 3
测试获取项目14的产品信息 >> 4
测试获取项目15的产品信息 >> 5
测试获取不存在的项目的产品信息 >> 0

*/

$projectIDList = array('11', '12', '13', '14', '15', '1000001');

$product = new productTest('admin');

r($product->getProductIDByProjectTest($projectIDList[0])) && p() && e('1'); // 测试获取项目11的产品信息
r($product->getProductIDByProjectTest($projectIDList[1])) && p() && e('2'); // 测试获取项目12的产品信息
r($product->getProductIDByProjectTest($projectIDList[2])) && p() && e('3'); // 测试获取项目13的产品信息
r($product->getProductIDByProjectTest($projectIDList[3])) && p() && e('4'); // 测试获取项目14的产品信息
r($product->getProductIDByProjectTest($projectIDList[4])) && p() && e('5'); // 测试获取项目15的产品信息
r($product->getProductIDByProjectTest($projectIDList[5])) && p() && e('0'); // 测试获取不存在的项目的产品信息