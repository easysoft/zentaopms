#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->gen(10);
zenData('release')->gen(10);
zenData('story')->gen(0);
zenData('project')->gen(10);
zenData('productplan')->gen(10);
zenData('projectproduct')->gen(10);
su('admin');

/**

title=测试 myModel->getproducts();
timeout=0
cid=17287

- 获取所有产品的数量属性allCount @10
- 获取所有未关闭的产品数量属性unclosedCount @10
- 获取产品中序号为0的产品属性
 - 第0条的id属性 @1
 - 第0条的name属性 @正常产品1
 - 第0条的releases属性 @5
- 获取产品中序号为1的产品属性
 - 第1条的id属性 @2
 - 第1条的name属性 @正常产品2
 - 第1条的releases属性 @2
- 获取产品中序号为2的产品属性
 - 第2条的id属性 @3
 - 第2条的name属性 @正常产品3
 - 第2条的releases属性 @0

*/

global $tester;
$tester->loadModel('my');

$products = $tester->my->getProducts();
r($products) && p('allCount')      && e(10); // 获取所有产品的数量
r($products) && p('unclosedCount') && e(10); // 获取所有未关闭的产品数量

$products = $products->products;
r($products) && p('0:id,name,releases') && e('1,正常产品1,5'); // 获取产品中序号为0的产品属性
r($products) && p('1:id,name,releases') && e('2,正常产品2,2'); // 获取产品中序号为1的产品属性
r($products) && p('2:id,name,releases') && e('3,正常产品3,0'); // 获取产品中序号为2的产品属性
