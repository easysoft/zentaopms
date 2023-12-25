#!/usr/bin/env php
<?php

/**

title=productModel->processRoadmap();
cid=0

- 测试获取产品1下的发布roadmap
 - 第0条的id属性 @1
 - 第1条的id属性 @11
 - 第2条的id属性 @21
 - 第3条的id属性 @31
- 测试获取产品2下的发布roadmap第0条的id属性 @7
- 测试获取产品3下的发布roadmap第0条的id属性 @3
- 测试获取产品4下的发布roadmap第0条的id属性 @9
- 测试获取产品5下的发布roadmap第0条的id属性 @5
- 测试获取不存在产品下的发布roadmap @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('build')->gen(50);
zdTable('project')->gen(50);
zdTable('branch')->gen(50);

$release = zdTable('release');
$release->product->range('1-5');
$release->branch->range('0-1');
$release->gen(50);

$productIDList = array(0, 1, 2, 3, 4, 5, 1000001);

$product = new productTest('admin');
r($product->processRoadmapTest($productIDList[1]))        && p('0:id;1:id;2:id;3:id') && e('1,11,21,31'); // 测试获取产品1下的发布roadmap
r($product->processRoadmapTest($productIDList[2]))        && p('0:id')                && e('7');          // 测试获取产品2下的发布roadmap
r($product->processRoadmapTest($productIDList[3]))        && p('0:id')                && e('3');          // 测试获取产品3下的发布roadmap
r($product->processRoadmapTest($productIDList[4]))        && p('0:id')                && e('9');          // 测试获取产品4下的发布roadmap
r($product->processRoadmapTest($productIDList[5]))        && p('0:id')                && e('5');          // 测试获取产品5下的发布roadmap
r(count($product->processRoadmapTest($productIDList[6]))) && p()                      && e('0');          // 测试获取不存在产品下的发布roadmap
