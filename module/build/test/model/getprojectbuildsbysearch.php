#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getProjectBuildsBySearch();
timeout=0
cid=15497

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('build')->loadYaml('build')->gen(20);
zenData('project')->loadYaml('execution')->gen(30);
zenData('product')->loadYaml('product')->gen(10);
su('admin');

$projectIDList = array(0, 17);
$queryIDList   = array(0, 4);
$count         = array(0, 1);

$build = new buildModelTest();
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[0], $queryIDList[0])) && p('8:project,name') && e('100,版本8'); // 项目id为0查询
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[1], $queryIDList[0])) && p('7')              && e('0');         // 正常项目查询
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[0], $queryIDList[0])) && p()                 && e('20');        // 项目id为0查询统计
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[1], $queryIDList[0])) && p()                 && e('0');         // 正常项目查询统计
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[0], $queryIDList[1])) && p('7:project,name') && e('61,版本7');  // 查询条件查询
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[0], $queryIDList[1])) && p()                 && e('20');        // 查询条件查询统计
