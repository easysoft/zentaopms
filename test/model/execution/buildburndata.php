#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->buildBurnDataTest();
cid=1
pid=1

敏捷执行燃尽图数据 >> 7/1
瀑布执行燃尽图数据 >> [0,0,0,0,0,0,0,0,0,0,0]
看板执行燃尽图数据 >> [20,18,16,14,12,10,8,6,4,2,0]
结果统计 >> 3

*/

$executionIDList = array('101', '131', '161');
$count           = array('0', '1');

$execution = new executionTest();
r($execution->buildBurnDataTest($executionIDList[0], $count[0])) && p('labels:0') && e('7/1');                           // 敏捷执行燃尽图数据
r($execution->buildBurnDataTest($executionIDList[1], $count[0])) && p('burnLine') && e('[0,0,0,0,0,0,0,0,0,0,0]');       // 瀑布执行燃尽图数据
r($execution->buildBurnDataTest($executionIDList[2], $count[0])) && p('baseLine') && e('[20,18,16,14,12,10,8,6,4,2,0]'); // 看板执行燃尽图数据
r($execution->buildBurnDataTest($executionIDList[0], $count[1])) && p()           && e('3');                             // 结果统计