#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(2);
zenData('action')->loadYaml('action')->gen(10, true, false);
su('user1');

$beginDate  = date('Y-m-d');
$endDate1   = $beginDate;
$endDate2   = date('Y-m-d', strtotime($beginDate . ' +1 day'));
$endDate3   = date('Y-m-d', strtotime($beginDate . ' +1 month'));
$endDate4   = date('Y-m-d', strtotime($beginDate . ' +1 year'));
$endDate5   = date('Y-m-d', strtotime($beginDate . ' +1 year +2 months +4 days'));
$expect1    = helper::getDateInterval($beginDate, $endDate1);
$expect2    = helper::getDateInterval($beginDate, $endDate2);
$expect3    = helper::getDateInterval($beginDate, $endDate3);
$expect4    = helper::getDateInterval($beginDate, $endDate4);
$expect5    = helper::getDateInterval($beginDate, $endDate5);
$expected1  = "{$expect1->year},{$expect1->month},{$expect1->day},{$expect1->hour},{$expect1->minute},{$expect1->secound}";
$expected2  = "{$expect2->year},{$expect2->month},{$expect2->day},{$expect2->hour},{$expect2->minute},{$expect2->secound}";
$expected3  = "{$expect3->year},{$expect3->month},{$expect3->day},{$expect3->hour},{$expect3->minute},{$expect3->secound}";
$expected4  = "{$expect4->year},{$expect4->month},{$expect4->day},{$expect4->hour},{$expect4->minute},{$expect4->secound}";
$expected5  = "{$expect5->year},{$expect5->month},{$expect5->day},{$expect5->hour},{$expect5->minute},{$expect5->secound}";

/**

title=测试 adminModel::genDateUsed();
timeout=0
cid=14978

- 执行admin模块的genDateUsed方法，参数是当天日期 @1
- 执行admin模块的genDateUsed方法，参数是 +1 day @1
- 执行admin模块的genDateUsed方法，参数是 +1 month @1
- 执行admin模块的genDateUsed方法，参数是 +1 year @1
- 执行admin模块的genDateUsed方法，参数是 +1 year +2 months +4 days @1

*/

global $tester;

$tester->loadModel('admin');

$result1 = $tester->admin->genDateUsed($endDate1);
$result2 = $tester->admin->genDateUsed($endDate2);
$result3 = $tester->admin->genDateUsed($endDate3);
$result4 = $tester->admin->genDateUsed($endDate4);
$result5 = $tester->admin->genDateUsed($endDate5);

$actual1 = "{$result1->year},{$result1->month},{$result1->day},{$result1->hour},{$result1->minute},{$result1->secound}";
$actual2 = "{$result2->year},{$result2->month},{$result2->day},{$result2->hour},{$result2->minute},{$result2->secound}";
$actual3 = "{$result3->year},{$result3->month},{$result3->day},{$result3->hour},{$result3->minute},{$result3->secound}";
$actual4 = "{$result4->year},{$result4->month},{$result4->day},{$result4->hour},{$result4->minute},{$result4->secound}";
$actual5 = "{$result5->year},{$result5->month},{$result5->day},{$result5->hour},{$result5->minute},{$result5->secound}";

r($actual1 === $expected1 ? 1 : 0) && p() && e('1');
r($actual2 === $expected2 ? 1 : 0) && p() && e('1');
r($actual3 === $expected3 ? 1 : 0) && p() && e('1');
r($actual4 === $expected4 ? 1 : 0) && p() && e('1');
r($actual5 === $expected5 ? 1 : 0) && p() && e('1');
