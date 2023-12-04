#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(50);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->getWorkingDays();
cid=1
pid=1

*/

$holiday = new holidayTest();
$begin   = array('-14 day', '-7 day', '+4 day');
$end     = array('+7 day', '+14 day', '+6day');

r($holiday->getWorkingDaysTest($begin[0], $end[0])) && p() && e('56'); //返回处于 14天前 到 7天后 之间的工作日。
r($holiday->getWorkingDaysTest($begin[0], $end[1])) && p() && e('56'); //返回处于 14天前 到 14天后 之间的工作日。
r($holiday->getWorkingDaysTest($begin[1], $end[0])) && p() && e('20'); //返回处于 7天前 到 7天后 之间的工作日。
r($holiday->getWorkingDaysTest($begin[1], $end[1])) && p() && e('20'); //返回处于 7天前 到 14天后 之间的工作日。
r($holiday->getWorkingDaysTest($begin[2], $end[0])) && p() && e('0'); //测试当结束日期小于开始日期时。
r($holiday->getWorkingDaysTest($begin[2], $end[2])) && p() && e('0'); //测试当数据库没有工作日记录时。
