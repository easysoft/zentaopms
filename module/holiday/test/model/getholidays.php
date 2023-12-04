#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(50);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->getHolidays();
cid=1
pid=1

*/

$holiday = new holidayTest();
$begin   = array('-1 month +1 day', '-25 day', '-2 month -2 day');
$end     = array('-20 day', '-24 day', '-2 month');

r($holiday->getHolidaysTest($begin[0], $end[0])) && p() && e('10'); // 返回处于 一个月前 到 20天前 之间的休假日期。
r($holiday->getHolidaysTest($begin[0], $end[1])) && p() && e('6');  // 返回处于 一个月前 到 24天前 之间的休假日期。
r($holiday->getHolidaysTest($begin[1], $end[2])) && p() && e('0');  // 测试当结束日期小于开始日期时。
r($holiday->getHolidaysTest($begin[1], $end[1])) && p() && e('2');  // 返回处于 25天前 到 24天前 之间的休假日期。
r($holiday->getHolidaysTest($begin[2], $end[2])) && p() && e('0');  // 测试当数据库没有休假记录时。
