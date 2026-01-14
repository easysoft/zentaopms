#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 actionModel->computeBeginAndEnd();
timeout=0
cid=14883

- 测试计算all的日期 @0
- 测试计算today的日期 @1
- 测试计算yesterday的日期 @1
- 测试计算twodaysago的日期 @1
- 测试计算latest3days的日期 @1
- 测试计算thisweek的日期 @1
- 测试计算lastweek的日期 @1
- 测试计算thismonth的日期 @1
- 测试计算lastmonth的日期 @1
- 测试返回具体的日期 @1
- 测试返回具体日期的结束日期 @1
- 测试返回具体日期的开始日期 @1

*/

$typeList      = array('all', 'today', 'yesterday', 'twodaysago', 'latest3days', 'thisweek', 'lastweek', 'thismonth', 'lastmonth');
$dateList      = array('', '2025-04-23');
$directionList = array('', 'pre', 'next');

$action = new actionModelTest();

r($action->computeBeginAndEndTest($typeList[0], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算all的日期
r($action->computeBeginAndEndTest($typeList[1], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算today的日期
r($action->computeBeginAndEndTest($typeList[2], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算yesterday的日期
r($action->computeBeginAndEndTest($typeList[3], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算twodaysago的日期
r($action->computeBeginAndEndTest($typeList[4], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算latest3days的日期
r($action->computeBeginAndEndTest($typeList[5], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算thisweek的日期
r($action->computeBeginAndEndTest($typeList[6], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算lastweek的日期
r($action->computeBeginAndEndTest($typeList[7], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算thismonth的日期
r($action->computeBeginAndEndTest($typeList[8], $dateList[0], $directionList[0])) && p() && e('1'); // 测试计算lastmonth的日期
r($action->computeBeginAndEndTest($typeList[0], $dateList[1], $directionList[0])) && p() && e('1'); // 测试返回具体的日期
r($action->computeBeginAndEndTest($typeList[0], $dateList[1], $directionList[1])) && p() && e('1'); // 测试返回具体日期的结束日期
r($action->computeBeginAndEndTest($typeList[0], $dateList[1], $directionList[2])) && p() && e('1'); // 测试返回具体日期的开始日期
