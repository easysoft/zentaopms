#!/usr/bin/env php
<?php
/**

title=projectModel->computeSchedule();
timeout=0
cid=1

- 测试开始日期和结束日期为 2025-01-01 和 2025-12-31
 - 属性begin @2025-01-01
 - 属性end @2025-12-31
 - 属性minWorkHours @7.0
 - 属性maxWorkHours @8.0
- 测试开始日期和结束日期为 2025-01-01 和 2026-12-31
 - 属性begin @2025-01-01
 - 属性end @2026-12-31
 - 属性minWorkHours @7.0
 - 属性maxWorkHours @8.0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('holiday')->gen(0);
zenData('user')->gen(5);
su('admin');

$begins = array('', '2025-01-01');
$ends   = array('', '2025-12-31', '2026-12-31');

$project = new stdclass();
$project->schedule = json_encode(array('calendar' => 0, 'begin' => '2025-01-01', 'end' => '2026-12-31'));

$schedule = array('calendar' => 0, 'begin' => '2025-03-01', 'end' => '2026-10-31');

$projectTest = new projectModelTest();
r($projectTest->computeScheduleTest($begins[1], $ends[1], $schedule, $project)) && p('begin,end,minWorkHours,maxWorkHours') && e('2025-01-01,2025-12-31,7.0,8.0'); // 测试开始日期和结束日期为 2025-01-01 和 2025-12-31
r($projectTest->computeScheduleTest($begins[1], $ends[2], $schedule, $project)) && p('begin,end,minWorkHours,maxWorkHours') && e('2025-01-01,2026-12-31,7.0,8.0'); // 测试开始日期和结束日期为 2025-01-01 和 2026-12-31
