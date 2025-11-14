#!/usr/bin/env php
<?php

/**

title=productTao->getProjectProductList();
timeout=0
cid=17553

- 测试传入空的产品ID列表 @0
- 测试传入产品ID列表
 - 第61条的product属性 @3
 - 第61条的project属性 @61
- 测试传入产品ID列表 @0
- 测试传入不存在的产品ID列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('user')->gen(5);
zenData('project')->loadYaml('program')->gen(30);
zenData('product')->loadYaml('product')->gen(20);
zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);

$productTester = new productTest();

r($productTester->getProjectProductListTest($productIdList[0]))    && p() && e('0');   // 测试传入空的产品ID列表
r($productTester->getProjectProductListTest($productIdList[1])[3]) && p('61:product,project')  && e('3,61');  // 测试传入产品ID列表
r($productTester->getProjectProductListTest($productIdList[1])[4]) && p('') && e('0'); // 测试传入产品ID列表
r($productTester->getProjectProductListTest($productIdList[2]))    && p()   && e('0'); // 测试传入不存在的产品ID列表