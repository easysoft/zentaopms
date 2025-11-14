#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

su('admin');
zenData('project')->loadYaml('project')->gen(5);

/**

title=测试 projectModel->getLinkedProductsCount();
timeout=0
cid=17910

- 测试敏捷项目没有选择产品时获取的数量 @0
- 测试瀑布项目没有选择产品时获取的数量 @0
- 测试看板项目没有选择产品时获取的数量 @0
- 测试项目型敏捷项目获取的数量 @0
- 测试瀑布项目选择产品时获取的数量 @3
- 测试项目型看板项目选择产品时获取的数量 @0

*/

$projectIdList   = array(1, 2, 3);
$productsList[0] = array();
$productsList[1] = array(1, 2, 3);

$projectTester = new projectTest();
r($projectTester->getLinkedProductsCountTest($projectIdList[0], $productsList[0])) && p() && e('0'); // 测试敏捷项目没有选择产品时获取的数量
r($projectTester->getLinkedProductsCountTest($projectIdList[1], $productsList[0])) && p() && e('0'); // 测试瀑布项目没有选择产品时获取的数量
r($projectTester->getLinkedProductsCountTest($projectIdList[2], $productsList[0])) && p() && e('0'); // 测试看板项目没有选择产品时获取的数量
r($projectTester->getLinkedProductsCountTest($projectIdList[0], $productsList[1])) && p() && e('0'); // 测试项目型敏捷项目获取的数量
r($projectTester->getLinkedProductsCountTest($projectIdList[1], $productsList[1])) && p() && e('3'); // 测试瀑布项目选择产品时获取的数量
r($projectTester->getLinkedProductsCountTest($projectIdList[2], $productsList[1])) && p() && e('0'); // 测试项目型看板项目选择产品时获取的数量
