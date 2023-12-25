#!/usr/bin/env php
<?php

/**

title=productModel->getMultiBranchPairs();
cid=0

- 测试获取不传入项目集的产品id @,7,9,11,13,15

- 测试获取项目集1的产品id @,13

- 测试获取不存在项目集的产品id @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$product = zdTable('product');
$product->type->range('normal{5},branch{5},platform{5}');
$product->deleted->range('0,1');
$product->gen(15);

$programIDList = array(0, 1, 1000001);

$product = new productTest('admin');

r($product->getMultiBranchPairsTest($programIDList[0])) && p() && e(',7,9,11,13,15'); // 测试获取不传入项目集的产品id
r($product->getMultiBranchPairsTest($programIDList[1])) && p() && e(',13');           // 测试获取项目集1的产品id
r($product->getMultiBranchPairsTest($programIDList[2])) && p() && e('0');             // 测试获取不存在项目集的产品id
