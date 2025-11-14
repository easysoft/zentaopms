#!/usr/bin/env php
<?php

/**

title=productTao->getProjectCountPairs();
timeout=0
cid=17552

- 测试传入空的产品ID列表 @0
- 测试传入产品ID列表属性1 @1
- 测试传入产品ID列表属性2 @1
- 测试传入产品ID列表属性3 @1
- 测试传入产品ID列表属性4 @1
- 测试传入不存在的产品ID列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('project')->loadYaml('program')->gen(30);
zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);

global $tester;
$tester->loadModel('product');
r($tester->product->getProjectCountPairs($productIdList[0])) && p()    && e('0'); // 测试传入空的产品ID列表
r($tester->product->getProjectCountPairs($productIdList[1])) && p('1') && e('1'); // 测试传入产品ID列表
r($tester->product->getProjectCountPairs($productIdList[1])) && p('2') && e('1'); // 测试传入产品ID列表
r($tester->product->getProjectCountPairs($productIdList[1])) && p('3') && e('1'); // 测试传入产品ID列表
r($tester->product->getProjectCountPairs($productIdList[1])) && p('4') && e('1'); // 测试传入产品ID列表
r($tester->product->getProjectCountPairs($productIdList[2])) && p()    && e('0'); // 测试传入不存在的产品ID列表
