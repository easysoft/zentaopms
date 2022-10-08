#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTeams2ImportTest();
cid=1
pid=1

无效数据查询 >> 无数据
正常数据查询 >> 迭代91
正常数据查询统计 >> 1

*/

$executionID = '101';
$accountList = array('test7', 'test82');
$count       = array('0','1');

$execution = new executionTest();
r($execution->getTeams2ImportTest($accountList[0], $executionID, $count[0])) && p()      && e('无数据'); // 无效数据查询
r($execution->getTeams2ImportTest($accountList[1], $executionID, $count[0])) && p('191') && e('迭代91'); // 正常数据查询
r($execution->getTeams2ImportTest($accountList[1], $executionID, $count[1])) && p()      && e('1');      // 正常数据查询统计