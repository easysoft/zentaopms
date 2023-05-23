#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

/**
title=测试通过项目id查询关联的产品id productModel->getProductIDByProject();
cid=1
pid=1
*/

zdTable('projectproduct')->config('projectproduct')->gen(50);

$projectIDList = array('11', '12', '13', '14', '15', '1000001');

$product = new productTest('admin');

r($product->getProductIDByProjectTest($projectIDList[0])) && p() && e('1'); // 测试获取项目11对应的产品ID
r($product->getProductIDByProjectTest($projectIDList[1])) && p() && e('2'); // 测试获取项目12对应的产品ID
r($product->getProductIDByProjectTest($projectIDList[2])) && p() && e('3'); // 测试获取项目13对应的产品ID
r($product->getProductIDByProjectTest($projectIDList[3])) && p() && e('4'); // 测试获取项目14对应的产品ID
r($product->getProductIDByProjectTest($projectIDList[4])) && p() && e('5'); // 测试获取项目15对应的产品ID
r($product->getProductIDByProjectTest($projectIDList[5])) && p() && e('0'); // 测试获取不存在的项目对应的产品ID
