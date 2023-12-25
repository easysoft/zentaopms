#!/usr/bin/env php
<?php

/**

title=测试productModel->getRoadmapOfPlans();
cid=0

- 获取产品ID为1的产品计划数据 @3
- 获取产品ID为1下主干的产品计划数据 @3
- 获取产品ID为1下分支1的产品计划数据 @3
- 获取产品ID为1的产品计划数据 @3
- 获取产品ID为6的产品计划数据 @3
- 获取产品ID为6下主干的产品计划数据 @3
- 获取产品ID为6下分支1的产品计划数据 @3
- 获取产品ID为6的产品计划数据 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('user')->gen(5);
zdTable('productplan')->config('productplan')->gen(30);
zdTable('release')->config('release')->gen(30);
su('admin');

$productIdList = array(1, 6);
$branchList    = array('all', '0', '1');
$countList     = array(0, 1);

$productTester = new productTest();
r(count($productTester->getRoadmapOfPlansTest($productIdList[0], $branchList[0], $countList[0]))) && p() && e('3'); // 获取产品ID为1的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[0], $branchList[1], $countList[0]))) && p() && e('3'); // 获取产品ID为1下主干的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[0], $branchList[2], $countList[0]))) && p() && e('3'); // 获取产品ID为1下分支1的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[0], $branchList[0], $countList[1]))) && p() && e('3'); // 获取产品ID为1的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[1], $branchList[0], $countList[0]))) && p() && e('3'); // 获取产品ID为6的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[1], $branchList[1], $countList[0]))) && p() && e('3'); // 获取产品ID为6下主干的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[1], $branchList[2], $countList[0]))) && p() && e('3'); // 获取产品ID为6下分支1的产品计划数据
r(count($productTester->getRoadmapOfPlansTest($productIdList[1], $branchList[0], $countList[1]))) && p() && e('3'); // 获取产品ID为6的产品计划数据
