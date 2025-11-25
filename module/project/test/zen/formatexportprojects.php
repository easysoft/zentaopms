#!/usr/bin/env php
<?php
/**

title=测试 projectZen::formatExportProjects();
timeout=0
cid=17941

- 格式化所有的项目并按照排序倒序排列的数量 @10
- 格式化未关闭的项目并按照排序倒序排列的数量 @6
- 格式化所有的项目并按照id倒序排列的数量
 - 第10条的id属性 @10
 - 第10条的name属性 @敏捷项目10
 - 第10条的status属性 @未开始
- 格式化未关闭的项目并按照id倒序排列的数量
 - 第10条的id属性 @10
 - 第10条的name属性 @敏捷项目10
 - 第10条的status属性 @未开始

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';
su('admin');
zenData('project')->loadYaml('project')->gen(10);

global $tester, $app;
$app->rawModule = 'project';
$app->rawMethod = 'export';

$projectTester = new projectZenTest();

$statusList  = array('all', 'undone');
$orderByList = array('order_desc', 'id_desc');

r(count($projectTester->formatExportProjectsTest($statusList[0], $orderByList[0]))) && p()                    && e('10');                   // 格式化所有的项目并按照排序倒序排列的数量
r(count($projectTester->formatExportProjectsTest($statusList[1], $orderByList[0]))) && p()                    && e('6');                    // 格式化未关闭的项目并按照排序倒序排列的数量
r($projectTester->formatExportProjectsTest($statusList[0], $orderByList[1]))        && p('10:id,name,status') && e('10,敏捷项目10,未开始'); // 格式化所有的项目并按照id倒序排列的数量
r($projectTester->formatExportProjectsTest($statusList[1], $orderByList[1]))        && p('10:id,name,status') && e('10,敏捷项目10,未开始'); // 格式化未关闭的项目并按照id倒序排列的数量
