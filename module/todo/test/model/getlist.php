#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoModel->getList();
cid=1
pid=0
*/

$typeList = array('today', 'yesterday', 'thisweek', 'lastweek', 'thismonth', 'lastmonth', 'thisseason', 'thisyear', 'future', 'before', 'cycle');

$todo = new todoTest();

zdTable('todo')->config('getlist')->gen(9);

global $tester;
$tester->loadModel('todo');

$day         = date('Y-m-d'); //今天
$lastday     = date('Y-m-d', strtotime('-1 day')); //昨天
$week        = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //本周一
$month       = date('Y-m-01'); //本月1日
$lastweekday = date('Y-m-d', strtotime('-7 day')); //上周的今天

$season = ceil((date('n')) / 3); //当月是第几季度
$season = date('Y-m-d', mktime(0, 0, 0, (int)$season * 3 - 3 + 1, 1, (int)date('Y'))); //本季度第一天

$defaultNum  = '0'; //默认天数
$dayNum      = '1'; //修改天数为今日的数量
$lastDayNum  = '2'; //修改天数为昨天的数量
$last7DayNum = '1'; //修改天数为7天前的数量

r($tester->todo->editDate(array(1),   date('Y-m-d'))) && p() && e('1');  // 修改id为1的待办的日期
r($tester->todo->editDate(array(2, 3), date('Y-m-d', strtotime('-1 day')))) && p() && e('1');  // 修改id为2,3的待办的日期
r($tester->todo->editDate(array(4),   $lastweekday)) && p() && e('1');  // 修改id为2,3的待办的日期

/* 本周上周实际待办数量 */
$thisWeekNum = $dayNum;
$lastWeekNum = $lastDayNum;

if(date('w') != 1){
    $thisWeekNum = $dayNum + $lastDayNum;
    $lastWeekNum = $defaultNum;
}
if(strtotime($week)- 7*86400 <= strtotime($lastweekday)) $lastWeekNum += $last7DayNum;

/* 本月上月实际待办数量 */
$thisMonthNum = $dayNum;
$lastMonthNum = strtotime($lastweekday) >= strtotime($month) ? $defaultNum : $last7DayNum;
if(strtotime($month) <= strtotime($lastday))     $thisMonthNum += $lastDayNum;
if(strtotime($month) <= strtotime($lastweekday)) $thisMonthNum += $last7DayNum;
if($month == $day) $lastMonthNum += $lastDayNum;

/* 本季度实际待办数量 */
$thisSeasonNum = $dayNum;
if(strtotime($season) <= strtotime($lastday)) $thisSeasonNum += $lastDayNum;
if(strtotime($season) <= strtotime($lastweekday)) $thisSeasonNum += $lastWeekNum;

$thisweek   = $todo->getListTest($typeList[2]) == $thisWeekNum   ? '1' : '0';
$lastweek   = $todo->getListTest($typeList[3]) == $lastWeekNum   ? '1' : '0';
$thismonth  = $todo->getListTest($typeList[4]) == $thisMonthNum  ? '1' : '0';
$lastmonth  = $todo->getListTest($typeList[5]) == $lastMonthNum  ? '1' : '0';
$thisseason = $todo->getListTest($typeList[6]) == $thisSeasonNum ? '1' : '0';

r($thisweek)  && p() && e('1'); // 获取type为thisweek 当前用户的代办数量
r($lastweek)  && p() && e('1'); // 获取type为lastweek 当前用户的代办数量
r($thismonth) && p() && e('1'); // 获取type为thismonth 当前用户的代办数量
r($lastmonth) && p() && e('1'); // 获取type为lastmonth 当前用户的代办数量

r($todo->getListTest($typeList[0]))  && p() && e('1'); // 获取type为today 当前用户的代办数量
r($todo->getListTest($typeList[1]))  && p() && e('2'); // 获取type为yesterday 当前用户的代办数量
r($todo->getListTest($typeList[7]))  && p() && e('9'); // 获取type为thisyear 当前用户的代办数量
r($todo->getListTest($typeList[8]))  && p() && e('0'); // 获取type为future 当前用户的代办数量
r($todo->getListTest($typeList[9]))  && p() && e('9'); // 获取type为before 当前用户的代办数量
r($todo->getListTest($typeList[10])) && p() && e('0'); // 获取type为cycle 当前用户的代办数量
