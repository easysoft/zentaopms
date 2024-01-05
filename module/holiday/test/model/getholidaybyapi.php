#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(50);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->getHolidayByAPI();
timeout=0
cid=1

- 返回 本年 的节假日。 @2
- 返回 上年 的节假日。 @2
- 返回 下年 的节假日。 @2
- 返回 不设置年份 的节假日。 @2

*/

$holiday = new holidayTest();
$year    = array('this year', 'last year', 'next year', '');

/* Mock数据只包含元旦和国庆假期。*/
r($holiday->getHolidayByAPITest($year[0])) && p() && e('2'); //返回 本年 的节假日。
r($holiday->getHolidayByAPITest($year[1])) && p() && e('2'); //返回 上年 的节假日。
r($holiday->getHolidayByAPITest($year[2])) && p() && e('2'); //返回 下年 的节假日。
r($holiday->getHolidayByAPITest($year[3])) && p() && e('2'); //返回 不设置年份 的节假日。