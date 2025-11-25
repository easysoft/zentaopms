#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('project')->loadYaml('program')->gen(20);
zenData('doclib')->gen(1);

/**

title=测试 projectModel->createDocLib();
timeout=0
cid=17892

*/

$projectTester = new projectTest();

$programIdList = array(1, 2, 0);
$projectIdList = array(11, 60, 61, 100);

r($projectTester->createDocLibTest($projectIdList[0], $programIdList[0])) && p('name,project') && e('项目主库,11');  // 测试给有项目集的敏捷项目创建文档库
r($projectTester->createDocLibTest($projectIdList[1], $programIdList[1])) && p('name,project') && e('项目主库,60');  // 测试给有项目集的瀑布项目创建文档库
r($projectTester->createDocLibTest($projectIdList[2], $programIdList[2])) && p('name,project') && e('项目主库,61');  // 测试给没有项目集的瀑布项目创建文档库
r($projectTester->createDocLibTest($projectIdList[3], $programIdList[2])) && p('name,project') && e('项目主库,100'); // 测试给没有项目集的看板项目创建文档库
