#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(50);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->getActualWorkingDays();
cid=1
pid=1

*/

$holiday = new holidayTest();
$begin   = array('-14 day', '-7 day', '+7 day', '+14 day', '');
$end     = array('+7 day', '+14 day', '');

r($holiday->getActualWorkingDaysTest($begin[0], $end[0])) && p() && e('19'); // 返回处于 14天前 到 7天后 之间的实际工作日。
r($holiday->getActualWorkingDaysTest($begin[0], $end[1])) && p() && e('24'); // 返回处于 14天前 到 14天后 之间的实际工作日。
r($holiday->getActualWorkingDaysTest($begin[1], $end[0])) && p() && e('13'); // 返回处于 7天前 到 7天后 之间的实际工作日。
r($holiday->getActualWorkingDaysTest($begin[1], $end[1])) && p() && e('18'); // 返回处于 7天前 到 14天后 之间的实际工作日。
r($holiday->getActualWorkingDaysTest($begin[2], $end[0])) && p() && e('1');  // 测试开始和结束日期相同的实际工作日 工作。
r($holiday->getActualWorkingDaysTest($begin[3], $end[2])) && p() && e('0');  // 测试开始和结束日期相同的实际工作日 节假日。
r($holiday->getactualworkingdaystest($begin[3], $end[0])) && p() && e('0');  // 测试当结束日期小于开始日期时。
r($holiday->getactualworkingdaystest($begin[4], $end[0])) && p() && e('0');  // 测试输入开始日期为空。
r($holiday->getactualworkingdaystest($begin[0], $end[2])) && p() && e('0');  // 测试输入结束日期为空。
r($holiday->getactualworkingdaystest($begin[4], $end[2])) && p() && e('0');  // 测试输入开始和结束日期为空。
