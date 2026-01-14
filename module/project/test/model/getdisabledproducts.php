#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 projectModel->getDisabledProducts();
timeout=0
cid=0

- 测试获取敏捷项目不可修改产品 @0
- 测试获取以产品创建的瀑布项目不可修改产品属性2 @项目已经关联了该产品中的研发需求，不能取消关联，您可以取消关联研发需求后再操作。
- 测试获取以产品创建的瀑布项目不可修改产品属性3 @该产品已经创建了阶段，如需解除与项目的关联，请删除已创建的阶段后再操作。
- 测试获取以产品创建的瀑布项目不可修改产品属性4 @该产品已经创建了阶段并关联了研发需求，如需解除与项目的关联，请先解除研发需求的关联关系，然后删除已创建的阶段后再操作。
- 测试获取以项目创建的瀑布项目不可修改产品属性5 @该产品的研发需求已经关联到了项目和执行中，请先解除研发需求与项目和执行的关联后再操作。
- 测试获取以项目创建的瀑布项目不可修改产品属性6 @项目已经关联了该产品中的研发需求，不能取消关联，您可以取消关联研发需求后再操作。

*/

zenData('project')->loadYaml('project')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);
zenData('story')->loadYaml('story')->gen(20);
zenData('projectstory')->loadYaml('projectstory')->gen(20);

$projectIdList = range(1, 6);

$projectTester = new projectModelTest();
r($projectTester->getDisabledProductsTest($projectIdList[0])) && p()    && e('0');                                                                                                                      // 测试获取敏捷项目不可修改产品
r($projectTester->getDisabledProductsTest($projectIdList[1])) && p('2') && e('项目已经关联了该产品中的研发需求，不能取消关联，您可以取消关联研发需求后再操作。');                                       // 测试获取以产品创建的瀑布项目不可修改产品
r($projectTester->getDisabledProductsTest($projectIdList[2])) && p('3') && e('该产品已经创建了阶段，如需解除与项目的关联，请删除已创建的阶段后再操作。');                                               // 测试获取以产品创建的瀑布项目不可修改产品
r($projectTester->getDisabledProductsTest($projectIdList[3])) && p('4') && e('该产品已经创建了阶段并关联了研发需求，如需解除与项目的关联，请先解除研发需求的关联关系，然后删除已创建的阶段后再操作。'); // 测试获取以产品创建的瀑布项目不可修改产品
r($projectTester->getDisabledProductsTest($projectIdList[4])) && p('5') && e('该产品的研发需求已经关联到了项目和执行中，请先解除研发需求与项目和执行的关联后再操作。');                                 // 测试获取以项目创建的瀑布项目不可修改产品
r($projectTester->getDisabledProductsTest($projectIdList[5])) && p('6') && e('项目已经关联了该产品中的研发需求，不能取消关联，您可以取消关联研发需求后再操作。');                                       // 测试获取以项目创建的瀑布项目不可修改产品
