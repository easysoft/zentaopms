#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getProjectBuildsBySearch();
cid=1
pid=1

项目id为0查询 >> 18,项目版本版本8
正常项目查询 >> 17,项目版本版本7
项目id为空查询 >> 19,项目版本版本9
搜索条件id为空查询 >> 16,项目版本版本6
项目id为0查询统计 >> 10
正常项目查询统计 >> 1
项目id为空查询统计 >> 10
搜索条件id为空查询统计 >> 10
查询条件查询 >> 17,项目版本版本7
查询条件查询统计 >> 1

*/

$projectIDList = array('0', '17', '');
$queryIDList   = array('0', '4', '');
$count         = array('0', '1');

$build = new buildTest();

r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[0], $queryIDList[0])) && p('8:project,name') && e('18,项目版本版本8');//项目id为0查询
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[1], $queryIDList[0])) && p('7:project,name') && e('17,项目版本版本7');//正常项目查询
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[2], $queryIDList[0])) && p('9:project,name') && e('19,项目版本版本9');//项目id为空查询
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[0], $queryIDList[2])) && p('6:project,name') && e('16,项目版本版本6');//搜索条件id为空查询
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[0], $queryIDList[0])) && p()                 && e('10');              //项目id为0查询统计
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[1], $queryIDList[0])) && p()                 && e('1');               //正常项目查询统计
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[2], $queryIDList[0])) && p()                 && e('10');              //项目id为空查询统计
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[0], $queryIDList[2])) && p()                 && e('10');              //搜索条件id为空查询统计
r($build->getProjectBuildsBySearchTest($count[0], $projectIDList[0], $queryIDList[1])) && p('7:project,name') && e('17,项目版本版本7');//查询条件查询
r($build->getProjectBuildsBySearchTest($count[1], $projectIDList[0], $queryIDList[1])) && p()                 && e('1');               //查询条件查询统计