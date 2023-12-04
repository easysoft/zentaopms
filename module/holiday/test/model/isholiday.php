#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->isHoliday();
cid=1
pid=1

测试节假日2022年4月10日 >> It is a holiday
测试节假日2022年4月16日 >> It is a holiday
测试补班2022年5月7日 >> It is not a holiday
测试补班2022年5月13日 >> It is not a holiday

*/
$holidays    = array('-1 month', '-1 month +4 day');
$workingDays = array('-1 month -1 day', '-1 month -3 day');

$holiday = new holidayTest();

r($holiday->isHolidayTest($holidays[0])) && p()    && e('It is a holiday');     // 测试节假日 一个月之前的一天
r($holiday->isHolidayTest($holidays[1])) && p()    && e('It is a holiday');     // 测试节假日 一个月加4天之前 的一天
r($holiday->isHolidayTest($workingDays[0])) && p() && e('It is not a holiday'); // 测试补班 一个月加1天 之前的一天
r($holiday->isHolidayTest($workingDays[1])) && p() && e('It is not a holiday'); // 测试补班 一个月加3天 之前的一天
