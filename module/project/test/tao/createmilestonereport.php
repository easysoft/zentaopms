#!/usr/bin/env php
<?php
/**

title=测试 projectModel->createMilestoneReport();
timeout=0
cid=17893

- 测试创建里程碑报告
 - 属性title @里程碑报告
 - 属性project @1
 - 属性templateType @projectReport
 - 属性reportModule @milestone
 - 属性addedBy @system

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->loadYaml('project')->gen(1);
zenData('doc')->gen(0);
zenData('user')->gen(5);
su('admin');

global $tester;
$projectModel = $tester->loadModel('project');
$projectModel->createMilestoneReport(1);

$report = $tester->dao->select('*')->from(TABLE_DOC)->fetch();
r($report) && p('title,project,templateType,reportModule,addedBy') && e('里程碑报告,1,projectReport,milestone,system'); // 测试创建里程碑报告
