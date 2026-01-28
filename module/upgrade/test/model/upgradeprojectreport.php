#!/usr/bin/env php
<?php
/**

title=测试 projectModel->upgradeProjectReport();
timeout=0
cid=19564

- 测试升级开始日期跟项目开始日期相同的周报
 - 属性title @第 1 周( 2025-01-01 ~ 2025-01-07)
 - 属性project @1
 - 属性templateType @projectReport
 - 属性weeklyDate @20250101
 - 属性addedBy @system
- 测试升级开始日期跟项目开始日期不同的周报
 - 属性title @第 5 周( 2025-09-08 ~ 2025-09-14)
 - 属性project @1
 - 属性templateType @projectReport
 - 属性weeklyDate @20250908
 - 属性addedBy @system

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doc')->gen(0);
zenData('user')->gen(5);
su('admin');

$data[] = array('weekStart' => '2025-01-01', 'projectBegin' => '2025-01-01', 'project' => 1);
$data[] = array('weekStart' => '2025-09-08', 'projectBegin' => '2025-08-11', 'project' => 1);

$upgradeTester = new upgradeModelTest();
r($upgradeTester->upgradeProjectReportTest($data[0])) && p('title,project,templateType,weeklyDate,addedBy') && e('第 1 周( 2025-01-01 ~ 2025-01-07),1,projectReport,20250101,system'); // 测试升级开始日期跟项目开始日期相同的周报
r($upgradeTester->upgradeProjectReportTest($data[1])) && p('title,project,templateType,weeklyDate,addedBy') && e('第 5 周( 2025-09-08 ~ 2025-09-14),1,projectReport,20250908,system'); // 测试升级开始日期跟项目开始日期不同的周报
