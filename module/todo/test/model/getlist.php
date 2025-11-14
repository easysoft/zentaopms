#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**

title=测试 todoModel->getList();
timeout=0
cid=19260

- 获取当前用户今天的待办数量 @1
- 获取当前用户昨天的待办数量 @1
- 获取当前用户本周的待办数量 @7
- 获取当前用户上周的待办数量 @7
- 获取当前用户本月的待办数量 @1
- 获取当前用户上月的待办数量 @1
- 获取当前用户本季度的待办数量 @1
- 获取当前用户本年的待办数量 @1
- 获取当前用户待定的待办数量 @0
- 获取当前用户今天之前的待办数量 @1
- 获取当前用户周期性待办数量 @0

*/

$typeList = array('today', 'yesterday', 'thisweek', 'lastweek', 'thismonth', 'lastmonth', 'thisseason', 'thisyear', 'future', 'before', 'cycle');

$todo = new todoTest();

$thisYearDay = date('L') ? 366 : 365;
$lastYearDay = date('L', strtotime("-1 years")) ? 366 : 365;
zenData('todo')->loadYaml('getlist')->gen($thisYearDay + $lastYearDay);

global $tester;
$tester->loadModel('todo');

$thisMonthDay = date('t');
$lastMonthDay = date('t', strtotime(date('Y-m-01') . ' -1 day'));

$startMonth = ceil(date('n') / 3) * 3 - 2;
$start      = date("Y-$startMonth-1");
$end        = date("Y-m-d", strtotime("+3 months", strtotime($start)));
$thisSseasonDay = (strtotime($end) - strtotime($start)) / (60 * 60 * 24);

$lastYearToday = strtotime('last year');
$thisYearToday = strtotime('this year');

$beforeDay = (time() - strtotime('-1 years')) / (60 * 60 * 24) + 1;

r($todo->getListTest($typeList[0]))                    && p() && e('1'); // 获取当前用户今天的待办数量
r($todo->getListTest($typeList[1]))                    && p() && e('1'); // 获取当前用户昨天的待办数量
r($todo->getListTest($typeList[2]))                    && p() && e('7'); // 获取当前用户本周的待办数量
r($todo->getListTest($typeList[3]))                    && p() && e('7'); // 获取当前用户上周的待办数量
r($todo->getListTest($typeList[4]) == $thisMonthDay)   && p() && e('1'); // 获取当前用户本月的待办数量
r($todo->getListTest($typeList[5]) == $lastMonthDay)   && p() && e('1'); // 获取当前用户上月的待办数量
r($todo->getListTest($typeList[6]) == $thisSseasonDay) && p() && e('1'); // 获取当前用户本季度的待办数量
r($todo->getListTest($typeList[7]) == $thisYearDay)    && p() && e('1'); // 获取当前用户本年的待办数量
r($todo->getListTest($typeList[8]))                    && p() && e('0'); // 获取当前用户待定的待办数量
r($todo->getListTest($typeList[9]) == $beforeDay)      && p() && e('1'); // 获取当前用户今天之前的待办数量
r($todo->getListTest($typeList[10]))                   && p() && e('0'); // 获取当前用户周期性待办数量