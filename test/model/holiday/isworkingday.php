#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->isWorkingDay();
cid=1
pid=1

测试不是工作日 >> 0
测试是工作日 >> 1
测试日期格式不对 >> 0

*/

$holiday = new holidayTest();
$date    = array('2022-03-01', '2022-04-09', '2002-2-2');

r($holiday->isWorkingDayTest($date[0])) && p() && e('0'); // 测试不是工作日
r($holiday->isWorkingDayTest($date[1])) && p() && e('1'); // 测试是工作日
r($holiday->isWorkingDayTest($date[2])) && p() && e('0'); // 测试日期格式不对