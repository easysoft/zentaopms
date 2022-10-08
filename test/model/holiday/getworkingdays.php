#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->getWorkingDays();
cid=1
pid=1

返回处于2022-3-3到2022-4-6之间的工作日。 >> 0
返回处于2022-3-3到2022-4-8之间的工作日。 >> 2
测试当结束日期大于开始日期时。 >> 0
返回处于2022-4-6到2022-4-8之间的工作日。 >> 2
测试当数据库没有工作日记录时。 >> 0

*/

$holiday = new holidayTest();

$begin   = array('2022-03-03', '2022-04-07');
$end     = array('2022-04-06', '2022-04-08', '2022-03-05');

r($holiday->getWorkingDaysTest($begin[0], $end[0])) && p() && e('0'); //返回处于2022-3-3到2022-4-6之间的工作日。
r($holiday->getWorkingDaysTest($begin[0], $end[1])) && p() && e('2'); //返回处于2022-3-3到2022-4-8之间的工作日。
r($holiday->getWorkingDaysTest($begin[1], $end[0])) && p() && e('0'); //测试当结束日期大于开始日期时。
r($holiday->getWorkingDaysTest($begin[1], $end[1])) && p() && e('2'); //返回处于2022-4-6到2022-4-8之间的工作日。
r($holiday->getWorkingDaysTest($begin[0], $end[2])) && p() && e('0'); //测试当数据库没有工作日记录时。