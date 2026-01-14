#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('program')->gen(20);
zenData('product')->loadYaml('product')->gen(10);
zenData('team')->loadYaml('team')->gen(30);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);

/**

title=测试 projectModel::linkProducts();
timeout=0
cid=17864

*/

$projectIdList = array(11, 60, 100);
$productIdList = array(0, 1, 2, 3);
$branches      = array(0, 1, 2, 3);
$plans         = array(1, 2, 3, 4);

$projectTester = new projectModelTest();
r(count($projectTester->linkProductsTest($projectIdList[0])))                                    && p() && e('0'); // 测试敏捷项目没有关联产品的数量
r(count($projectTester->linkProductsTest($projectIdList[0], $productIdList)))                    && p() && e('3'); // 测试敏捷项目关联产品的数量
r(count($projectTester->linkProductsTest($projectIdList[0], $productIdList, $branches)))         && p() && e('3'); // 测试敏捷项目关联产品、分支的数量
r(count($projectTester->linkProductsTest($projectIdList[0], $productIdList, $branches, $plans))) && p() && e('3'); // 测试敏捷项目关联产品、分支、计划的数量
r(count($projectTester->linkProductsTest($projectIdList[1])))                                    && p() && e('0'); // 测试瀑布项目没有关联产品的数量
r(count($projectTester->linkProductsTest($projectIdList[1], $productIdList)))                    && p() && e('3'); // 测试瀑布项目关联产品的数量
r(count($projectTester->linkProductsTest($projectIdList[1], $productIdList, $branches)))         && p() && e('3'); // 测试瀑布项目关联产品、分支的数量
r(count($projectTester->linkProductsTest($projectIdList[1], $productIdList, $branches, $plans))) && p() && e('3'); // 测试瀑布项目关联产品、分支、计划的数量
r(count($projectTester->linkProductsTest($projectIdList[2])))                                    && p() && e('0'); // 测试看板项目没有关联产品的数量
r(count($projectTester->linkProductsTest($projectIdList[2], $productIdList)))                    && p() && e('3'); // 测试看板项目关联产品的数量
r(count($projectTester->linkProductsTest($projectIdList[2], $productIdList, $branches)))         && p() && e('3'); // 测试看板项目关联产品、分支的数量
r(count($projectTester->linkProductsTest($projectIdList[2], $productIdList, $branches, $plans))) && p() && e('3'); // 测试看板项目关联产品、分支、计划的数量

r($projectTester->linkProductsTest($projectIdList[0]))                                    && p()                    && e('0');     // 测试敏捷项目没有关联产品
r($projectTester->linkProductsTest($projectIdList[0], $productIdList))                    && p('0:project,product') && e('11,1');  // 测试敏捷项目关联产品
r($projectTester->linkProductsTest($projectIdList[0], $productIdList, $branches))         && p('0:project,branch')  && e('11,1');  // 测试敏捷项目关联产品、分支
r($projectTester->linkProductsTest($projectIdList[0], $productIdList, $branches, $plans)) && p('0:project,plan')    && e('11,0');  // 测试敏捷项目关联产品、分支、计划
r($projectTester->linkProductsTest($projectIdList[1]))                                    && p()                    && e('0');     // 测试瀑布项目没有关联产品
r($projectTester->linkProductsTest($projectIdList[1], $productIdList))                    && p('0:project,product') && e('60,1');  // 测试瀑布项目关联产品
r($projectTester->linkProductsTest($projectIdList[1], $productIdList, $branches))         && p('0:project,branch')  && e('60,1');  // 测试瀑布项目关联产品、分支
r($projectTester->linkProductsTest($projectIdList[1], $productIdList, $branches, $plans)) && p('0:project,plan')    && e('60,0');  // 测试瀑布项目关联产品、分支、计划
r($projectTester->linkProductsTest($projectIdList[2]))                                    && p()                    && e('0');     // 测试看板项目没有关联产品
r($projectTester->linkProductsTest($projectIdList[2], $productIdList))                    && p('0:project,product') && e('100,1'); // 测试看板项目关联产品
r($projectTester->linkProductsTest($projectIdList[2], $productIdList, $branches))         && p('0:project,branch')  && e('100,1'); // 测试看板项目关联产品、分支
r($projectTester->linkProductsTest($projectIdList[2], $productIdList, $branches, $plans)) && p('0:project,plan')    && e('100,0'); // 测试看板项目关联产品、分支、计划
