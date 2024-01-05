#!/usr/bin/env php
<?php
/**

title=测试 holidayModel->getWorkingDays();
cid=1

- 返回 2023-01-28 ~ 2024-01-08 的工作日。 @7
- 返回 2023-01-28 ~ 2024-01-15 的工作日。 @7
- 返回 2023-12-25 ~ 2024-01-08 的工作日。 @0
- 返回 2023-12-25 ~ 2024-01-15 的工作日。 @0
- 测试当结束日期小于开始日期时。 @0
- 测试当数据库没有工作日记录时。 @0

*/
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->config('holiday')->gen(24);
zdTable('user')->gen(1);

su('admin');

$holiday = new holidayTest();
$begin   = array('2023-01-28', '2023-12-25', '2024-01-05');
$end     = array('2024-01-08', '2024-01-15', '2024-01-07');

r($holiday->getWorkingDaysTest($begin[0], $end[0])) && p() && e('7'); //返回 2023-01-28 ~ 2024-01-08 的工作日。
r($holiday->getWorkingDaysTest($begin[0], $end[1])) && p() && e('7'); //返回 2023-01-28 ~ 2024-01-15 的工作日。
r($holiday->getWorkingDaysTest($begin[1], $end[0])) && p() && e('0'); //返回 2023-12-25 ~ 2024-01-08 的工作日。
r($holiday->getWorkingDaysTest($begin[1], $end[1])) && p() && e('0'); //返回 2023-12-25 ~ 2024-01-15 的工作日。
r($holiday->getWorkingDaysTest($begin[2], $end[0])) && p() && e('0'); //测试当结束日期小于开始日期时。
r($holiday->getWorkingDaysTest($begin[2], $end[2])) && p() && e('0'); //测试当数据库没有工作日记录时。
