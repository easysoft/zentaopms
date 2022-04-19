#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getMultiBranchPairs();
cid=1
pid=1

测试获取项目集1的产品id >> ,46,57,68,79,90
测试获取项目集2的产品id >> ,47,58,69,80,91
测试获取项目集3的产品id >> ,48,59,70,81,92
测试获取项目集4的产品id >> ,49,60,71,82,93
测试获取项目集5的产品id >> ,50,61,72,83,94
测试获取不存在项目集的产品id >> 0

*/

$programIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getMultiBranchPairsTest($programIDList[0])) && p() && e(',46,57,68,79,90'); // 测试获取项目集1的产品id
r($product->getMultiBranchPairsTest($programIDList[1])) && p() && e(',47,58,69,80,91'); // 测试获取项目集2的产品id
r($product->getMultiBranchPairsTest($programIDList[2])) && p() && e(',48,59,70,81,92'); // 测试获取项目集3的产品id
r($product->getMultiBranchPairsTest($programIDList[3])) && p() && e(',49,60,71,82,93'); // 测试获取项目集4的产品id
r($product->getMultiBranchPairsTest($programIDList[4])) && p() && e(',50,61,72,83,94'); // 测试获取项目集5的产品id
r($product->getMultiBranchPairsTest($programIDList[5])) && p() && e('0');               // 测试获取不存在项目集的产品id