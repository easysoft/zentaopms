#!/usr/bin/env php
<?php

/**

title=测试productModel->activate();
cid=0

- 测试未关闭产品1 @0
- 测试未关闭产品2 @0
- 测试未关闭产品3 @0
- 测试未关闭产品4 @0
- 测试未关闭产品5 @0
- 测试关闭的产品1
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @normal
- 测试关闭的产品2
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @normal
- 测试关闭的产品3
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @normal
- 测试关闭的产品4
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @normal
- 测试关闭的产品5
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @normal
- 测试不存在的产品11 @0
- 测试不存在的产品0 @0
- 测试不存在的产品-1 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->config('product')->gen(10);

$normalProductIdList = array(1, 2, 3, 4, 5);
$closedProductIdList = array(6, 7, 8, 9, 10);
$wrongProductIdList  = array(11, 0, -1);

$productTester = new productTest();
r($productTester->activateTest($normalProductIdList[0])) && p() && e('0'); // 测试未关闭产品1
r($productTester->activateTest($normalProductIdList[1])) && p() && e('0'); // 测试未关闭产品2
r($productTester->activateTest($normalProductIdList[2])) && p() && e('0'); // 测试未关闭产品3
r($productTester->activateTest($normalProductIdList[3])) && p() && e('0'); // 测试未关闭产品4
r($productTester->activateTest($normalProductIdList[4])) && p() && e('0'); // 测试未关闭产品5

r($productTester->activateTest($closedProductIdList[0])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品1
r($productTester->activateTest($closedProductIdList[1])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品2
r($productTester->activateTest($closedProductIdList[2])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品3
r($productTester->activateTest($closedProductIdList[3])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品4
r($productTester->activateTest($closedProductIdList[4])) && p('0:field,old,new') && e('status,closed,normal'); // 测试关闭的产品5

r($productTester->activateTest($wrongProductIdList[0]))  && p(0) && e('0'); // 测试不存在的产品11
r($productTester->activateTest($wrongProductIdList[1]))  && p(0) && e('0'); // 测试不存在的产品0
r($productTester->activateTest($wrongProductIdList[2]))  && p(0) && e('0'); // 测试不存在的产品-1
