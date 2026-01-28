#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('product')->loadYaml('product')->gen(5);
zenData('project')->loadYaml('program')->gen(20);
zenData('projectproduct')->loadYaml('projectproduct')->gen(10);

/**

title=测试projectModel->updateShadowProduct();
timeout=0
cid=17880

- 测试更新项目型敏捷项目的产品信息
 - 第0条的id属性 @1
 - 第0条的name属性 @更新敏捷项目11
- 测试更新产品型瀑布项目的产品信息
 - 第0条的id属性 @2
 - 第0条的name属性 @产品2
- 测试更新项目型瀑布项目的产品信息
 - 第0条的id属性 @3
 - 第0条的name属性 @更新瀑布项目13
- 测试更新产品型看板项目的产品信息
 - 第0条的id属性 @4
 - 第0条的name属性 @产品4

*/

$projectIdList = array(11, 60, 61, 100);
$projectTester = new projectModelTest();

r($projectTester->updateShadowProductTest($projectIdList[0])) && p('0:id,name') && e('1,更新敏捷项目11'); // 测试更新项目型敏捷项目的产品信息
r($projectTester->updateShadowProductTest($projectIdList[1])) && p('0:id,name') && e('2,产品2');         // 测试更新产品型瀑布项目的产品信息
r($projectTester->updateShadowProductTest($projectIdList[2])) && p('0:id,name') && e('3,更新瀑布项目13'); // 测试更新项目型瀑布项目的产品信息
r($projectTester->updateShadowProductTest($projectIdList[3])) && p('0:id,name') && e('4,产品4');         // 测试更新产品型看板项目的产品信息
