#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('project')->loadYaml('program')->gen(20);
zenData('product')->loadYaml('product')->gen(10);
zenData('team')->loadYaml('team')->gen(30);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);

/**

title=测试 projectModel->updateProducts();
timeout=0
cid=17878

- 测试敏捷项目不关联产品的数量 @0
- 测试敏捷项目关联所属项目集的产品的数量 @3
- 测试敏捷项目关联其他项目集的产品的数量 @7
- 测试瀑布项目不关联产品的数量 @0
- 测试瀑布项目关联所属项目集的产品的数量 @3
- 测试瀑布项目关联其他项目集的产品的数量 @7
- 测试看板项目不关联产品的数量 @0
- 测试看板项目关联所属项目集的产品的数量 @3
- 测试看板项目关联其他项目集的产品的数量 @7
- 测试敏捷项目不关联产品 @0
- 测试敏捷项目关联所属项目集的产品
 - 第0条的project属性 @11
 - 第0条的product属性 @1
- 测试敏捷项目关联其他项目集的产品
 - 第6条的project属性 @11
 - 第6条的product属性 @7
- 测试瀑布项目不关联产品 @0
- 测试瀑布项目关联所属项目集的产品
 - 第0条的project属性 @60
 - 第0条的product属性 @1
- 测试瀑布项目关联其他项目集的产品
 - 第6条的project属性 @60
 - 第6条的product属性 @7
- 测试看板项目不关联产品 @0
- 测试看板项目关联所属项目集的产品
 - 第0条的project属性 @100
 - 第0条的product属性 @1
- 测试看板项目关联其他项目集的产品
 - 第6条的project属性 @100
 - 第6条的product属性 @7

*/

$projectIdList      = array(11, 60, 100);
$productIdList      = array(0, 1, 2, 3);
$otherProductIdList = array('4', '5', '6', '7');

$projectTester = new projectTest();
$noLinkProduct[0] = $projectTester->updateProductsTest($projectIdList[0]);
$noLinkProduct[1] = $projectTester->updateProductsTest($projectIdList[1]);
$noLinkProduct[2] = $projectTester->updateProductsTest($projectIdList[2]);

$linkProducts[0] = $projectTester->updateProductsTest($projectIdList[0], $productIdList);
$linkProducts[1] = $projectTester->updateProductsTest($projectIdList[1], $productIdList);
$linkProducts[2] = $projectTester->updateProductsTest($projectIdList[2], $productIdList);

$linkOtherProducts[0] = $projectTester->updateProductsTest($projectIdList[0], array(), $otherProductIdList);
$linkOtherProducts[1] = $projectTester->updateProductsTest($projectIdList[1], array(), $otherProductIdList);
$linkOtherProducts[2] = $projectTester->updateProductsTest($projectIdList[2], array(), $otherProductIdList);


r(count($noLinkProduct[0]))     && p() && e('0'); // 测试敏捷项目不关联产品的数量
r(count($linkProducts[0]))      && p() && e('3'); // 测试敏捷项目关联所属项目集的产品的数量
r(count($linkOtherProducts[0])) && p() && e('7'); // 测试敏捷项目关联其他项目集的产品的数量
r(count($noLinkProduct[1]))     && p() && e('0'); // 测试瀑布项目不关联产品的数量
r(count($linkProducts[1]))      && p() && e('3'); // 测试瀑布项目关联所属项目集的产品的数量
r(count($linkOtherProducts[1])) && p() && e('7'); // 测试瀑布项目关联其他项目集的产品的数量
r(count($noLinkProduct[2]))     && p() && e('0'); // 测试看板项目不关联产品的数量
r(count($linkProducts[2]))      && p() && e('3'); // 测试看板项目关联所属项目集的产品的数量
r(count($linkOtherProducts[2])) && p() && e('7'); // 测试看板项目关联其他项目集的产品的数量

r($noLinkProduct[0])     && p()                    && e('0');     // 测试敏捷项目不关联产品
r($linkProducts[0])      && p('0:project,product') && e('11,1');  // 测试敏捷项目关联所属项目集的产品
r($linkOtherProducts[0]) && p('6:project,product') && e('11,7');  // 测试敏捷项目关联其他项目集的产品
r($noLinkProduct[1])     && p()                    && e('0');     // 测试瀑布项目不关联产品
r($linkProducts[1])      && p('0:project,product') && e('60,1');  // 测试瀑布项目关联所属项目集的产品
r($linkOtherProducts[1]) && p('6:project,product') && e('60,7');  // 测试瀑布项目关联其他项目集的产品
r($noLinkProduct[2])     && p()                    && e('0');     // 测试看板项目不关联产品
r($linkProducts[2])      && p('0:project,product') && e('100,1'); // 测试看板项目关联所属项目集的产品
r($linkOtherProducts[2]) && p('6:project,product') && e('100,7'); // 测试看板项目关联其他项目集的产品
