#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->getExecutionBuilds();
cid=1
pid=1

全部执行版本查询 >> 107,执行版本版本17
单独执行版本查询 >> 107,执行版本版本17
不存在执行版本查询 >> 0
根据产品查询版本 >> 107,执行版本版本17
根据查询条件查询版本 >> 107,执行版本版本17
无查询条件查询版本 >> 107,执行版本版本17
全部执行版本查询统计 >> 20
单独执行版本查询统计 >> 1
无查询条件查询版本统计 >> 20

*/

$count           = array('0', '1');
$executionIDList = array('0', '107', '507');
$type            = array('all', 'product', 'bysearch', 'test');
$parm            = array(7, "t1.name = '执行版本版本17'", "test");

$build = new buildTest();

r($build->getExecutionBuildsTest($count[0], $executionIDList[0], $type[0]))           && p('17:execution,name') && e('107,执行版本版本17'); //全部执行版本查询
r($build->getExecutionBuildsTest($count[0], $executionIDList[1], $type[0]))           && p('17:execution,name') && e('107,执行版本版本17'); //单独执行版本查询
r($build->getExecutionBuildsTest($count[0], $executionIDList[2], $type[0]))           && p()                    && e('0');                  //不存在执行版本查询
r($build->getExecutionBuildsTest($count[0], $executionIDList[0], $type[1], $parm[0])) && p('17:execution,name') && e('107,执行版本版本17'); //根据产品查询版本
r($build->getExecutionBuildsTest($count[0], $executionIDList[1], $type[2], $parm[1])) && p('17:execution,name') && e('107,执行版本版本17'); //根据查询条件查询版本
r($build->getExecutionBuildsTest($count[0], $executionIDList[0], $type[3], $parm[2])) && p('17:execution,name') && e('107,执行版本版本17'); //无查询条件查询版本
r($build->getExecutionBuildsTest($count[1], $executionIDList[0], $type[0]))           && p()                    && e('20');                 //全部执行版本查询统计
r($build->getExecutionBuildsTest($count[1], $executionIDList[1], $type[0]))           && p()                    && e('1');                  //单独执行版本查询统计
r($build->getExecutionBuildsTest($count[1], $executionIDList[0], $type[3], $parm[2])) && p()                    && e('20');                 //无查询条件查询版本统计