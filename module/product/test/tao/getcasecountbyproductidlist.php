#!/usr/bin/env php
<?php

/**

title=productTao->getCaseCountByProductIdList();
cid=17543

- 测试传入空的产品ID列表 @0
- 测试传入产品ID列表属性1 @1
- 测试传入不存在产品ID列表 @0
- 测试传入包含空数据的产品ID列表属性11 @5
- 测试传入包含不存在数据的产品ID列表属性12 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('case')->loadYaml('case')->gen(30);
zenData('user')->gen(5);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(100, 110);
$productIdList[3] = array(0, 1, 2, 3);
$productIdList[4] = array(1, 2, 3, 100, 101, 102);

global $tester;
$tester->loadModel('product');
r($tester->product->getCaseCountByProductIdList($productIdList[0])) && p()   && e('0'); // 测试传入空的产品ID列表
r($tester->product->getCaseCountByProductIdList($productIdList[1])) && p(1)  && e('1'); // 测试传入产品ID列表
r($tester->product->getCaseCountByProductIdList($productIdList[2])) && p(0)  && e('0'); // 测试传入不存在产品ID列表
r($tester->product->getCaseCountByProductIdList($productIdList[3])) && p(11) && e('5'); // 测试传入包含空数据的产品ID列表
r($tester->product->getCaseCountByProductIdList($productIdList[4])) && p(12) && e('5'); // 测试传入包含不存在数据的产品ID列表
