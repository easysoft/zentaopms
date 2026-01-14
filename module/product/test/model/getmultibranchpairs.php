#!/usr/bin/env php
<?php

/**

title=productModel->getMultiBranchPairs();
timeout=0
cid=17496

- 测试获取不传入项目集的产品id @,7,9,11,13,15

- 测试获取项目集1的产品id @,13

- 测试获取项目集2的产品id @0
- 测试获取项目集3的产品id @,15

- 测试获取项目集4的产品id @0
- 测试获取项目集5的产品id @0
- 测试获取项目集14的产品id @0
- 测试获取项目集15的产品id @0
- 测试获取不存在项目集的产品id @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->type->range('normal{5},branch{5},platform{5}');
$product->deleted->range('0,1');
$product->gen(15);

$product = new productTest('admin');
r($product->getMultiBranchPairsTest(0))  && p() && e(',7,9,11,13,15'); // 测试获取不传入项目集的产品id
r($product->getMultiBranchPairsTest(1))  && p() && e(',13');           // 测试获取项目集1的产品id
r($product->getMultiBranchPairsTest(2))  && p() && e('0');             // 测试获取项目集2的产品id
r($product->getMultiBranchPairsTest(3))  && p() && e(',15');           // 测试获取项目集3的产品id
r($product->getMultiBranchPairsTest(4))  && p() && e('0');             // 测试获取项目集4的产品id
r($product->getMultiBranchPairsTest(5))  && p() && e('0');             // 测试获取项目集5的产品id
r($product->getMultiBranchPairsTest(14)) && p() && e('0');             // 测试获取项目集14的产品id
r($product->getMultiBranchPairsTest(15)) && p() && e('0');             // 测试获取项目集15的产品id
r($product->getMultiBranchPairsTest(16)) && p() && e('0');             // 测试获取不存在项目集的产品id
