#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getUpgradeProjectReports();
timeout=0
cid=19531

- 检查周报数量 @13
- 检查周报信息
 - 属性project @2
 - 属性weekStart @2025-01-08
 - 属性projectStatus @doing
 - 属性projectBegin @2024-12-30

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$projectTable = zenData('project')->loadYaml('project');
$projectTable->realBegan->range('`0000-00-00`,`2025-01-01`,`0000-00-00`,`2025-06-01`');
$projectTable->realEnd->range('`0000-00-00`{3},`2025-10-01`');
$projectTable->suspendedDate->range('`0000-00-00`{2},`2025-06-01`,`0000-00-00`');
$projectTable->gen(5);

zenData('weeklyreport')->loadYaml('weeklyreport')->gen(30);
zenData('user')->gen(5);
su('admin');

global $tester;
$upgradeModel = $tester->loadModel('upgrade');
$result       = $upgradeModel->getUpgradeProjectReports();

r(count($result)) && p()                                               && e('13');                             // 检查报告数量
r($result[5])     && p('project,weekStart,projectStatus,projectBegin') && e('2,2025-01-08,doing,2024-12-30'); // 检查周报信息
