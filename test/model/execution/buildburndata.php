#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->buildBurnDataTest();
cid=1
pid=1

敏捷执行关联用例 >> 101,1,1
瀑布执行关联用例 >> 131,43,169
看板执行关联用例 >> 161,68,269
敏捷执行关联用例统计 >> 4
瀑布执行关联用例统计 >> 4
看板执行关联用例统计 >> 4

*/

$executionIDList = array('101', '131', '161');
$count           = array('0', '1');

$execution = new executionTest();
r($execution->buildBurnDataTest($executionIDList[0], $count[0])) && p('labels:0') && e('7/1');                           // 敏捷执行燃尽图数据
r($execution->buildBurnDataTest($executionIDList[1], $count[0])) && p('burnLine') && e('[0,0,0,0,0,0,0,0,0,0,0]');       // 瀑布执行燃尽图数据
r($execution->buildBurnDataTest($executionIDList[2], $count[0])) && p('baseLine') && e('[20,18,16,14,12,10,8,6,4,2,0]'); // 看板执行燃尽图数据
r($execution->buildBurnDataTest($executionIDList[0], $count[1])) && p()           && e('3');                             // 结果统计
