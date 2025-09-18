#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel->upgradeMilestoneData();
timeout=0
cid=1

- 获取里程碑报告的数量 @5
- 获取里程碑报告的信息
 - 第0条的title属性 @阶段14里程碑报告
 - 第0条的project属性 @2
 - 第0条的execution属性 @14
 - 第0条的templateType属性 @projectReport
 - 第0条的module属性 @milestone

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
zenData('project')->loadYaml('execution')->gen(20);
zenData('doc')->gen(0);
zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyTest();
$result = $weeklyTester->upgradeMilestoneDataTest();
r(count($result)) && p()                                                && e('5');                                             // 获取里程碑报告的数量
r($result)        && p('0:title,project,execution,templateType,module') && e('阶段14里程碑报告,2,14,projectReport,milestone'); // 获取里程碑报告的信息
