#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

/**

title=productModel->processRoadmap();
cid=1
pid=1

*/

zdTable('product')->gen(50);
zdTable('release')->gen(50);
zdTable('build')->gen(50);
zdTable('project')->gen(50);
zdTable('branch')->gen(50);

$productIDList = array(0, 1, 2, 3, 4, 5, 1000001);

$product = new productTest('admin');
r($product->processRoadmapTest($productIDList[1], 0)) && p('0:id;1:id;2:id;3:id') && e('1,3,5,6'); // 测试获取产品1下的发布roadmap
r($product->processRoadmapTest($productIDList[2], 0)) && p('0:id')                && e('3');       // 测试获取产品2下的发布roadmap
r($product->processRoadmapTest($productIDList[3], 0)) && p('0:id')                && e('5');       // 测试获取产品3下的发布roadmap
r($product->processRoadmapTest($productIDList[4], 0)) && p()                      && e('6');       // 测试获取产品4下的发布roadmap
r($product->processRoadmapTest($productIDList[5], 0)) && p()                      && e('8');       // 测试获取产品5下的发布roadmap
r($product->processRoadmapTest($productIDList[6], 0)) && p()                      && e('0');       // 测试获取不存在产品下的发布roadmap
