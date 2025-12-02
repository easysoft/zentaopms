#!/usr/bin/env php
<?php

/**

title=productTao->getPlanList();
timeout=0
cid=17549

- 测试传入空的产品ID列表 @0
- 测试传入产品ID列表
 - 第5条的product属性 @2
 - 第5条的title属性 @计划5
- 测试传入产品ID列表
 - 第10条的product属性 @4
 - 第10条的title属性 @计划10
- 测试传入产品ID列表
 - 第15条的product属性 @5
 - 第15条的title属性 @计划15
- 测试传入产品ID列表
 - 第30条的product属性 @10
 - 第30条的title属性 @计划30
- 测试传入不存在产品ID列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('productplan')->loadYaml('productplan')->gen(30);
zenData('user')->gen(5);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);

global $tester;
$tester->loadModel('product');
r($tester->product->getPlanList($productIdList[0]))     && p()                   && e('0');         // 测试传入空的产品ID列表
r($tester->product->getPlanList($productIdList[1])[2])  && p('5:product,title')  && e('2,计划5');   // 测试传入产品ID列表
r($tester->product->getPlanList($productIdList[1])[4])  && p('10:product,title') && e('4,计划10');  // 测试传入产品ID列表
r($tester->product->getPlanList($productIdList[1])[5])  && p('15:product,title') && e('5,计划15');  // 测试传入产品ID列表
r($tester->product->getPlanList($productIdList[1])[10]) && p('30:product,title') && e('10,计划30'); // 测试传入产品ID列表
r($tester->product->getPlanList($productIdList[2]))     && p()                   && e('0');         // 测试传入不存在产品ID列表
