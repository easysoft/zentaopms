#!/usr/bin/env php
<?php
/**

title=projectModel->getWorkingDays();
timeout=0
cid=1

- 测试开始时间为空的情况 @0
- 测试开始时间为0000-00-00的情况 @0
- 测试结束时间为空的情况 @0
- 测试结束时间为0000-00-00的情况 @0
- 测试开始时间为2025-01-01，结束时间为2025-12-31的工作天数 @261
- 测试开始时间为2025-01-01，结束时间为2026-12-31的工作天数 @522

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('holiday')->gen(0);
zenData('user')->gen(5);
su('admin');

$begins = array('', '0000-00-00', '2025-01-01');
$ends   = array('', '0000-00-00', '2025-12-31', '2026-12-31');

$projectTest = new projectModelTest();

r($projectTest->getWorkingDaysTest($begins[0], $ends[2])) && p() && e('0'); // 测试开始时间为空的情况
r($projectTest->getWorkingDaysTest($begins[1], $ends[2])) && p() && e('0'); // 测试开始时间为0000-00-00的情况
r($projectTest->getWorkingDaysTest($begins[2], $ends[0])) && p() && e('0'); // 测试结束时间为空的情况
r($projectTest->getWorkingDaysTest($begins[2], $ends[1])) && p() && e('0'); // 测试结束时间为0000-00-00的情况

r(count($projectTest->getWorkingDaysTest($begins[2], $ends[2]))) && p() && e('261'); // 测试开始时间为2025-01-01，结束时间为2025-12-31的工作天数
r(count($projectTest->getWorkingDaysTest($begins[2], $ends[3]))) && p() && e('522'); // 测试开始时间为2025-01-01，结束时间为2026-12-31的工作天数
