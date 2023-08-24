#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

zdTable('product')->config('product')->gen(5);
zdTable('project')->config('program')->gen(20);
zdTable('projectproduct')->config('projectproduct')->gen(10);

/**

title=测试projectModel->updateShadowProduct();
timeout=0
cid=1

*/

$projectIdList = array(11, 60, 61, 100);
$projectTester = new project();

r($projectTester->updateShadowProductTest($projectIdList[0])) && p('0:name') && e('更新敏捷项目11'); // 测试更新项目型敏捷项目的产品信息
r($projectTester->updateShadowProductTest($projectIdList[1])) && p('0:name') && e('产品2');          // 测试更新产品型瀑布项目的产品信息
r($projectTester->updateShadowProductTest($projectIdList[2])) && p('0:name') && e('更新瀑布项目13'); // 测试更新项目型瀑布项目的产品信息
r($projectTester->updateShadowProductTest($projectIdList[3])) && p('0:name') && e('产品4');          // 测试更新产品型看板项目的产品信息
