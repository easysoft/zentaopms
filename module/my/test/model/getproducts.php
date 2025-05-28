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
cid=1

- 测试获取流程键值对属性allCount @10
- 测试获取流程键值对属性unclosedCount @10
- 测试获取流程键值对
 - 第0条的id属性 @1
 - 第0条的name属性 @正常产品1
 - 第0条的releases属性 @5
- 测试获取流程键值对
 - 第1条的id属性 @2
 - 第1条的name属性 @正常产品2
 - 第1条的releases属性 @2
- 测试获取流程键值对
 - 第2条的id属性 @3
 - 第2条的name属性 @正常产品3
 - 第2条的releases属性 @0

*/

global $tester;
$tester->loadModel('my');

$products = $tester->my->getProducts();
r($products) && p('allCount')      && e(10); // 测试获取流程键值对
r($products) && p('unclosedCount') && e(10); // 测试获取流程键值对

$products = $products->products;
r($products) && p('0:id,name,releases') && e('1,正常产品1,5'); // 测试获取流程键值对
r($products) && p('1:id,name,releases') && e('2,正常产品2,2'); // 测试获取流程键值对
r($products) && p('2:id,name,releases') && e('3,正常产品3,0'); // 测试获取流程键值对
