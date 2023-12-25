#!/usr/bin/env php
<?php

/**

title=测试productModel->statisticProgram();
cid=0

- 测试传入空数组 @0
- 测试传入存在的产品
 - 第44条的id属性 @44
 - 第44条的name属性 @多分支产品44
- 测试传入不存在的产品
 - 第50条的id属性 @50
 - 第50条的name属性 @多分支产品50

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('productplan')->gen(50);
zdTable('release')->gen(50);
zdTable('build')->gen(50);
zdTable('case')->gen(50);
zdTable('project')->gen(50);
zdTable('projectproduct')->gen(50);
zdTable('bug')->gen(50);
zdTable('doc')->gen(50);

$productIdList[0] = array();
$productIdList[1] = range(1, 50);
$productIdList[2] = range(50, 100);

$productTester = new productTest('admin');
r($productTester->statisticProgramTest($productIdList[0], 0))  && p()             && e('0');               // 测试传入空数组
r($productTester->statisticProgramTest($productIdList[1], 10)) && p('44:id,name') && e('44,多分支产品44'); // 测试传入存在的产品
r($productTester->statisticProgramTest($productIdList[2], 5))  && p('50:id,name') && e('50,多分支产品50'); // 测试传入不存在的产品
