#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->processBurnDataTest();
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
$itemCounts      = array('30', '60', '100');
$begin           = array('2022-01-15','2022-03-22');
$end             = array('2022-03-22','2022-01-15');
$count           = array('0','1');

$execution = new executionTest();
r($execution->processBurnDataTest($executionIDList[0], $itemCounts[0], $begin[0], $end[0], $count[1])) && p() && e('29'); // 敏捷执行查询统计
r($execution->processBurnDataTest($executionIDList[1], $itemCounts[1], $begin[0], $end[0], $count[1])) && p() && e('60'); // 瀑布执行查询统计
r($execution->processBurnDataTest($executionIDList[2], $itemCounts[2], $begin[0], $end[0], $count[1])) && p() && e('60'); // 看板执行查询统计
r($execution->processBurnDataTest($executionIDList[2], $itemCounts[2], $begin[1], $end[1], $count[1])) && p() && e('0');  // 错误时间查询统计
