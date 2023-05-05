#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
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
$day     = date('Y-m-d'); //今天
$lastday = date("Y-m-d", strtotime("-1 day")); //昨天
$week    = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)); //本周一
$month   = date('Y-m-01');
r($tester->todo->editDate(array(1),    date('Y-m-d'))) && p() && e('1');  // 修改id为1的待办的日期
r($tester->todo->editDate(array(2,3),    date("Y-m-d", strtotime("-1 day")))) && p() && e('1');  // 修改id为2,3的待办的日期

$thisWeekNum = '1';
$lastWeekNum = '0';
if(date("w") == 1) $thisWeekNum = '3';
if(date("w") != 1) $thisWeekNum = '2';

$thisMonthNum = '1';
$lastMonthNum = '0';
if(strtotime($month) >= strtotime($lastday)) $thisMonthNum = '3';
if(strtotime($month) <  strtotime($lastday)) $lastMonthNum = '2';

r($todo->getListTest($typeList[0]))  && p() && e('1'); // 获取type为today 当前用户的代办数量
r($todo->getListTest($typeList[1]))  && p() && e('2'); // 获取type为yesterday 当前用户的代办数量
r($todo->getListTest($typeList[6]))  && p() && e('9'); // 获取type为thisseason 当前用户的代办数量
r($todo->getListTest($typeList[7]))  && p() && e('9'); // 获取type为thisyear 当前用户的代办数量
r($todo->getListTest($typeList[8]))  && p() && e('0'); // 获取type为future 当前用户的代办数量
r($todo->getListTest($typeList[9]))  && p() && e('9'); // 获取type为before 当前用户的代办数量
r($todo->getListTest($typeList[10])) && p() && e('0'); // 获取type为cycle 当前用户的代办数量
