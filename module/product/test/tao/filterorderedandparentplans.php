#!/usr/bin/env php
<?php

/**

title=测试productTao->filterOrderedAndParentPlans();
cid=17540

- 获取产品ID为1的产品路线图数据 @2
- 获取产品ID为1下主干的产品路线图数据 @2
- 获取产品ID为1下分支1的产品路线图数据 @2
- 获取产品ID为6的产品路线图数据 @2
- 获取产品ID为6下主干的产品路线图数据 @2
- 获取产品ID为6下分支1的产品路线图数据 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('user')->gen(5);
zenData('productplan')->loadYaml('productplan')->gen(30);
zenData('release')->loadYaml('release')->gen(30);
su('admin');

$productIdList = array(1, 6);
$branchList    = array('all', '0', '1');

$productTester = new productTaoTest();
r(count($productTester->filterOrderedAndParentPlansTest($productIdList[0], $branchList[0]))) && p() && e('2'); // 获取产品ID为1的产品路线图数据
r(count($productTester->filterOrderedAndParentPlansTest($productIdList[0], $branchList[1]))) && p() && e('2'); // 获取产品ID为1下主干的产品路线图数据
r(count($productTester->filterOrderedAndParentPlansTest($productIdList[0], $branchList[2]))) && p() && e('2'); // 获取产品ID为1下分支1的产品路线图数据
r(count($productTester->filterOrderedAndParentPlansTest($productIdList[1], $branchList[0]))) && p() && e('2'); // 获取产品ID为6的产品路线图数据
r(count($productTester->filterOrderedAndParentPlansTest($productIdList[1], $branchList[1]))) && p() && e('2'); // 获取产品ID为6下主干的产品路线图数据
r(count($productTester->filterOrderedAndParentPlansTest($productIdList[1], $branchList[2]))) && p() && e('2'); // 获取产品ID为6下分支1的产品路线图数据
