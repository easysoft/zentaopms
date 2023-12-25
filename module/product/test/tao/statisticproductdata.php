#!/usr/bin/env php
<?php

/**

title=测试productModel->statisticProductData();
cid=0

- 测试传入空数组 @0
- 测试传入存在的产品
 - 第10条的id属性 @10
 - 第10条的name属性 @产品10
- 测试传入不存在的产品 @0
- 测试传入空数组 @0
- 测试传入存在的产品
 - 第30条的id属性 @30
 - 第30条的name属性 @产品30
- 测试传入不存在的产品 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->config('product')->gen(30);
zdTable('story')->gen(50);
zdTable('productplan')->gen(20);
zdTable('release')->gen(20);
zdTable('build')->gen(20);
zdTable('case')->gen(20);
zdTable('project')->config('program')->gen(20);
zdTable('projectproduct')->gen(30);
zdTable('bug')->gen(20);
zdTable('doc')->gen(20);
zdTable('user')->gen(5);

$productIdList[0] = array();
$productIdList[1] = range(1, 50);
$productIdList[2] = range(50, 100);
$typeList         = array('line', 'program');

$productTester = new productTest('admin');
r($productTester->statisticProductDataTest($typeList[0], $productIdList[0], 0))                && p()             && e('0');         // 测试传入空数组
r($productTester->statisticProductDataTest($typeList[0], $productIdList[1], 1)['products'])    && p('10:id,name') && e('10,产品10'); // 测试传入存在的产品
r($productTester->statisticProductDataTest($typeList[0], $productIdList[2], 100))              && p()             && e('0');         // 测试传入不存在的产品
r($productTester->statisticProductDataTest($typeList[1], $productIdList[0], 0))                && p()             && e('0');         // 测试传入空数组
r($productTester->statisticProductDataTest($typeList[1], $productIdList[1], 1)[3]['products']) && p('30:id,name') && e('30,产品30'); // 测试传入存在的产品
r($productTester->statisticProductDataTest($typeList[1], $productIdList[2], 100))              && p()             && e('0');         // 测试传入不存在的产品
