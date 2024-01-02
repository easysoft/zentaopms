#!/usr/bin/env php
<?php
/**

title=测试 holidayModel->isWorkingDay();
cid=1

- 测试不是工作日 @It is not a working day
- 测试是工作日 @It is a working day
- 测试日期格式不对 @It is not a working day

*/
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(10);
zdTable('user')->gen(1);

su('admin');

$holiday = new holidayTest();
$date    = array('-1 month', '-1 month +3 day', '');

r($holiday->isWorkingDayTest($date[0])) && p() && e('It is not a working day'); // 测试不是工作日
r($holiday->isWorkingDayTest($date[1])) && p() && e('It is a working day');     // 测试是工作日
r($holiday->isWorkingDayTest($date[2])) && p() && e('It is not a working day'); // 测试日期格式不对
