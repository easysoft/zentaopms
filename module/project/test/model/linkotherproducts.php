#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('program')->gen(20);
zenData('team')->loadYaml('team')->gen(30);
zenData('product')->loadYaml('product')->gen(10);
zenData('projectproduct')->gen(0);

/**

title=测试 projectModel::linkOtherProducts();
timeout=0
cid=17863

- 测试敏捷项目关联空产品 @1
- 测试瀑布项目关联空产品 @1
- 测试看板项目关联空产品 @1
- 测试敏捷项目关联产品 @1
- 测试瀑布项目关联产品 @1
- 测试看板项目关联产品 @1

*/

$projectIdList = array(11, 60, 100);
$productIdList = array('1', '2', '3');

$projectTester = new projectModelTest();
r($projectTester->linkOtherProductsTest($projectIdList[0], array()))        && p() && e('1'); // 测试敏捷项目关联空产品
r($projectTester->linkOtherProductsTest($projectIdList[1], array()))        && p() && e('1'); // 测试瀑布项目关联空产品
r($projectTester->linkOtherProductsTest($projectIdList[2], array()))        && p() && e('1'); // 测试看板项目关联空产品
r($projectTester->linkOtherProductsTest($projectIdList[0], $productIdList)) && p() && e('1'); // 测试敏捷项目关联产品
r($projectTester->linkOtherProductsTest($projectIdList[1], $productIdList)) && p() && e('1'); // 测试瀑布项目关联产品
r($projectTester->linkOtherProductsTest($projectIdList[2], $productIdList)) && p() && e('1'); // 测试看板项目关联产品
