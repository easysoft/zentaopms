#!/usr/bin/env php
<?php

/**

title=测试productModel->statisticProductData();
cid=17559

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
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('product')->loadYaml('product')->gen(30);
zenData('story')->gen(50);
zenData('productplan')->gen(20);
zenData('release')->gen(20);
zenData('build')->gen(20);
zenData('case')->gen(20);
zenData('project')->loadYaml('program')->gen(20);
zenData('projectproduct')->gen(30);
zenData('bug')->gen(20);
zenData('doc')->gen(20);
zenData('user')->gen(5);

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
