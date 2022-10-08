#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getExecutionBuildsBySearch();
cid=1
pid=1

执行id为0查询 >> 108,执行版本版本18
正常执行查询 >> 107,执行版本版本17
执行id为空查询 >> 109,执行版本版本19
搜索条件id为空查询 >> 106,执行版本版本16
执行id为0查询统计 >> 20
正常执行查询统计 >> 1
执行id为空查询统计 >> 20
搜索条件id为空查询统计 >> 20
查询条件查询 >> 107,执行版本版本17
查询条件查询统计 >> 1

*/

$executionIDList = array('0', '107', '');
$queryIDList     = array('0', '5', '');
$count           = array('0', '1');

$build = new buildTest();

r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[0], $queryIDList[0])) && p('18:execution,name') && e('108,执行版本版本18');//执行id为0查询
r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[1], $queryIDList[0])) && p('17:execution,name') && e('107,执行版本版本17');//正常执行查询
r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[2], $queryIDList[0])) && p('19:execution,name') && e('109,执行版本版本19');//执行id为空查询
r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[0], $queryIDList[2])) && p('16:execution,name') && e('106,执行版本版本16');//搜索条件id为空查询
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[0], $queryIDList[0])) && p()                    && e('20');                //执行id为0查询统计
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[1], $queryIDList[0])) && p()                    && e('1');                 //正常执行查询统计
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[2], $queryIDList[0])) && p()                    && e('20');                //执行id为空查询统计
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[0], $queryIDList[2])) && p()                    && e('20');                //搜索条件id为空查询统计
r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[0], $queryIDList[1])) && p('17:execution,name') && e('107,执行版本版本17');//查询条件查询
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[0], $queryIDList[1])) && p()                    && e('1');                 //查询条件查询统计