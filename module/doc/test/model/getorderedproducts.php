#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOrderedProducts();
cid=16119

- 获取系统中已排序的产品第0条的1属性 @产品1
- 获取系统中包括ID=1已排序的产品第0条的1属性 @产品1
- 获取系统中包括不存在ID已排序的产品第0条的1属性 @产品1
- 获取系统中已排序的产品数量 @3
- 获取系统中包括ID=1已排序的产品数量 @3
- 获取系统中包括不存在ID已排序的产品数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(20);
zenData('user')->gen(5);
su('admin');

$appends = array(0, 1, 100);

$docTester = new docModelTest();
r($docTester->getOrderedProductsTest($appends[0])) && p('0:1') && e('产品1'); // 获取系统中已排序的产品
r($docTester->getOrderedProductsTest($appends[1])) && p('0:1') && e('产品1'); // 获取系统中包括ID=1已排序的产品
r($docTester->getOrderedProductsTest($appends[2])) && p('0:1') && e('产品1'); // 获取系统中包括不存在ID已排序的产品

r(count($docTester->getOrderedProductsTest($appends[0]))) && p() && e('3'); // 获取系统中已排序的产品数量
r(count($docTester->getOrderedProductsTest($appends[1]))) && p() && e('3'); // 获取系统中包括ID=1已排序的产品数量
r(count($docTester->getOrderedProductsTest($appends[2]))) && p() && e('3'); // 获取系统中包括不存在ID已排序的产品数量
