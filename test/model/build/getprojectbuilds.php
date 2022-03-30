#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getProjectBuilds();
cid=1
pid=1

全部项目版本查询 >> 11,项目版本版本1
单独项目版本查询 >> 17,项目版本版本7
不存在项目版本查询 >> 0
根据产品查询版本 >> 17,项目版本版本7
根据查询条件查询版本 >> 17,项目版本版本7
无查询条件查询版本 >> 17,项目版本版本7
全部项目版本查询统计 >> 10
单独项目版本查询统计 >> 1
无查询条件查询版本统计 >> 10

*/

$count         = array('0', '1');
$projectIDList = array('0', '17', '57');
$type          = array('all', 'product', 'bysearch', 'test');
$parm          = array(7, "t1.name = '项目版本版本7'", "test");

$build = new buildTest();

r($build->getProjectBuildsTest($count[0], $projectIDList[0], $type[0]))           && p('1:project,name') && e('11,项目版本版本1'); //全部项目版本查询
r($build->getProjectBuildsTest($count[0], $projectIDList[1], $type[0]))           && p('7:project,name') && e('17,项目版本版本7'); //单独项目版本查询
r($build->getProjectBuildsTest($count[0], $projectIDList[2], $type[0]))           && p()                 && e('0');                //不存在项目版本查询
r($build->getProjectBuildsTest($count[0], $projectIDList[0], $type[1], $parm[0])) && p('7:project,name') && e('17,项目版本版本7'); //根据产品查询版本
r($build->getProjectBuildsTest($count[0], $projectIDList[1], $type[2], $parm[1])) && p('7:project,name') && e('17,项目版本版本7'); //根据查询条件查询版本
r($build->getProjectBuildsTest($count[0], $projectIDList[0], $type[3], $parm[2])) && p('7:project,name') && e('17,项目版本版本7'); //无查询条件查询版本
r($build->getProjectBuildsTest($count[1], $projectIDList[0], $type[0]))           && p()                 && e('10');               //全部项目版本查询统计
r($build->getProjectBuildsTest($count[1], $projectIDList[1], $type[0]))           && p()                 && e('1');                //单独项目版本查询统计
r($build->getProjectBuildsTest($count[1], $projectIDList[0], $type[3], $parm[2])) && p()                 && e('10');               //无查询条件查询版本统计