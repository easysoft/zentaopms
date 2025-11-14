#!/usr/bin/env php
<?php

/**

title=测试productModel->statisticProgram();
cid=17527

- 测试传入空数组 @0
- 测试传入存在的产品
 - 第44条的id属性 @44
 - 第44条的name属性 @多分支产品44
- 测试传入不存在的产品
 - 第50条的id属性 @50
 - 第50条的name属性 @多分支产品50

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->gen(50);
zenData('story')->gen(50);
zenData('productplan')->gen(50);
zenData('release')->gen(50);
zenData('build')->gen(50);
zenData('case')->gen(50);
zenData('project')->gen(50);
zenData('projectproduct')->gen(50);
zenData('bug')->gen(50);
zenData('doc')->gen(50);

$productIdList[0] = array();
$productIdList[1] = range(1, 50);
$productIdList[2] = range(50, 100);

$productTester = new productTest('admin');
r($productTester->statisticProgramTest($productIdList[0], 0))  && p()             && e('0');               // 测试传入空数组
r($productTester->statisticProgramTest($productIdList[1], 10)) && p('44:id,name') && e('44,多分支产品44'); // 测试传入存在的产品
r($productTester->statisticProgramTest($productIdList[2], 5))  && p('50:id,name') && e('50,多分支产品50'); // 测试传入不存在的产品
