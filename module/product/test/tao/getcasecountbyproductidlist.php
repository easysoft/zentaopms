#!/usr/bin/env php
<?php

/**

title=productTao->getCaseCountByProductIdList();
cid=0

- 测试传入空的产品ID列表 @5
- 测试传入产品ID列表属性1 @1
- 测试传入不存在产品ID列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('case')->loadYaml('case')->gen(30);
zenData('user')->gen(5);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(100, 110);

global $tester;
$tester->loadModel('product');
r($tester->product->getCaseCountByProductIdList($productIdList[0])) && p('0') && e('0'); // 测试传入空的产品ID列表
r($tester->product->getCaseCountByProductIdList($productIdList[1])) && p('1') && e('1'); // 测试传入产品ID列表
r($tester->product->getCaseCountByProductIdList($productIdList[2])) && p()    && e('0'); // 测试传入不存在产品ID列表
