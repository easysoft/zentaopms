#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
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
$holidays    = array('2022-04-10', '2022-04-16');
$workingDays = array('2022-05-07', '2022-05-13');

$holiday = new holidayTest();

r($holiday->isHolidayTest($holidays[0])) && p()    && e('It is a holiday'); // 测试节假日2022年4月10日
r($holiday->isHolidayTest($holidays[1])) && p()    && e('It is a holiday'); // 测试节假日2022年4月16日
r($holiday->isHolidayTest($workingDays[0])) && p() && e('It is not a holiday'); // 测试补班2022年5月7日
r($holiday->isHolidayTest($workingDays[1])) && p() && e('It is not a holiday'); // 测试补班2022年5月13日