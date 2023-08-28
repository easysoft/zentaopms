#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

zdTable('project')->config('program')->gen(20);
zdTable('team')->config('team')->gen(30);
zdTable('product')->config('product')->gen(10);
zdTable('projectproduct')->gen(0);

/**

title=测试 projectModel::linkOtherProducts();
timeout=0
cid=1

*/

$projectIdList = array(11, 60, 100);
$productIdList = array('1', '2', '3');

$projectTester = new Project();
r($projectTester->linkOtherProductsTest($projectIdList[0], array()))        && p() && e('1'); // 测试敏捷项目关联空产品
r($projectTester->linkOtherProductsTest($projectIdList[1], array()))        && p() && e('1'); // 测试瀑布项目关联空产品
r($projectTester->linkOtherProductsTest($projectIdList[2], array()))        && p() && e('1'); // 测试看板项目关联空产品
r($projectTester->linkOtherProductsTest($projectIdList[0], $productIdList)) && p() && e('1'); // 测试敏捷项目关联产品
r($projectTester->linkOtherProductsTest($projectIdList[1], $productIdList)) && p() && e('1'); // 测试瀑布项目关联产品
r($projectTester->linkOtherProductsTest($projectIdList[2], $productIdList)) && p() && e('1'); // 测试看板项目关联产品
