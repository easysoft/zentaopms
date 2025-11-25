#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
su('admin');
zenData('burn')->loadYaml('burn')->gen(15);

/**

title=测试executionModel->processBurnDataTest();
timeout=0
cid=16356

- 敏捷执行查询统计 @30
- 瀑布执行查询统计 @60
- 看板执行查询统计 @67
- 错误时间查询统计 @0
- 错误时间查询统计 @0

*/

$executionIDList = array(101, 106, 107);
$itemCounts      = array(30, 60, 100);
$begin           = array('2022-01-15','2022-03-22');
$end             = array('2022-03-22','2022-01-15');
$count           = array(0, 1);

$execution = new executionTest();
r($execution->processBurnDataTest($executionIDList[0], $itemCounts[0], $begin[0], $end[0], $count[1])) && p() && e('30'); // 敏捷执行查询统计
r($execution->processBurnDataTest($executionIDList[1], $itemCounts[1], $begin[0], $end[0], $count[1])) && p() && e('60'); // 瀑布执行查询统计
r($execution->processBurnDataTest($executionIDList[2], $itemCounts[2], $begin[0], $end[0], $count[1])) && p() && e('67'); // 看板执行查询统计
r($execution->processBurnDataTest($executionIDList[2], $itemCounts[2], $begin[1], $end[1], $count[1])) && p() && e('0');  // 错误时间查询统计
r($execution->processBurnDataTest($executionIDList[2], $itemCounts[1], $begin[1], $end[1], $count[1])) && p() && e('0');  // 错误时间查询统计
