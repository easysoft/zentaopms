#!/usr/bin/env php
<?php

/**

title=productModel->getLines();
cid=0

- 测试不传参数的情况 @20
- 测试传入空数组的情况 @20
- 测试获取项目集1的产品线 @5
- 测试获取项目集1,2的产品线 @10
- 测试获取项目集1,2,3的产品线 @15
- 测试获取项目集1,2,3,4的产品线 @20
- 测试获取项目集1,2,3,4,5的产品线 @20

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('module')->config('lines', $useCommon = true)->gen(20);
$product = $tester->loadModel('product');

$programIdList1 = array();
$programIdList2 = array(1);
$programIdList3 = array(1, 2);
$programIdList4 = array(1, 2, 3);
$programIdList5 = array(1, 2, 3, 4);
$programIdList6 = array(1, 2, 3, 4, 5);

r(count($product->getLines()))                && p() && e('20'); // 测试不传参数的情况
r(count($product->getLines($programIdList1))) && p() && e('20'); // 测试传入空数组的情况
r(count($product->getLines($programIdList2))) && p() && e('5');  // 测试获取项目集1的产品线
r(count($product->getLines($programIdList3))) && p() && e('10'); // 测试获取项目集1,2的产品线
r(count($product->getLines($programIdList4))) && p() && e('15'); // 测试获取项目集1,2,3的产品线
r(count($product->getLines($programIdList5))) && p() && e('20'); // 测试获取项目集1,2,3,4的产品线
r(count($product->getLines($programIdList6))) && p() && e('20'); // 测试获取项目集1,2,3,4,5的产品线
