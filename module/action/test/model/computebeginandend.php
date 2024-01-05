#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

/**

title=测试 actionModel->computeBeginAndEnd();
timeout=0
cid=1

- 测试计算all的日期 @1
- 测试计算today的日期 @1
- 测试计算yesterday的日期 @1
- 测试计算twodaysago的日期 @1
- 测试计算latest3days的日期 @1
- 测试计算thisweek的日期 @1
- 测试计算lastweek的日期 @1
- 测试计算thismonth的日期 @1
- 测试计算lastmonth的日期 @1

*/

$typeList = array('all', 'today', 'yesterday', 'twodaysago', 'latest3days', 'thisweek', 'lastweek', 'thismonth', 'lastmonth');

$action = new actionTest();

r($action->computeBeginAndEndTest($typeList[0])) && p() && e('1'); // 测试计算all的日期
r($action->computeBeginAndEndTest($typeList[1])) && p() && e('1'); // 测试计算today的日期
r($action->computeBeginAndEndTest($typeList[2])) && p() && e('1'); // 测试计算yesterday的日期
r($action->computeBeginAndEndTest($typeList[3])) && p() && e('1'); // 测试计算twodaysago的日期
r($action->computeBeginAndEndTest($typeList[4])) && p() && e('1'); // 测试计算latest3days的日期
r($action->computeBeginAndEndTest($typeList[5])) && p() && e('1'); // 测试计算thisweek的日期
r($action->computeBeginAndEndTest($typeList[6])) && p() && e('1'); // 测试计算lastweek的日期
r($action->computeBeginAndEndTest($typeList[7])) && p() && e('1'); // 测试计算thismonth的日期
r($action->computeBeginAndEndTest($typeList[8])) && p() && e('1'); // 测试计算lastmonth的日期