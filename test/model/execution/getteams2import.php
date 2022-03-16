#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTeams2ImportTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$executionID = '101';
$accountList = array('test7', 'test82');
$count       = array('0','1');

$execution = new executionTest();
r($execution->getTeams2ImportTest($accountList[0], $executionID, $count[0])) && p()      && e('无数据'); // 无效数据查询
r($execution->getTeams2ImportTest($accountList[1], $executionID, $count[0])) && p('191') && e('迭代91'); // 正常数据查询
r($execution->getTeams2ImportTest($accountList[1], $executionID, $count[1])) && p()      && e('1');      // 正常数据查询统计
