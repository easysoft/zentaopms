#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildGanttLinks()
cid=0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zdTable('relationoftasks')->gen(20);
$project = zdTable('project');
$project->type->range('stage');
$project->attribute->range('devel');
$project->begin->range('`2023-09-28`');
$project->end->range('`2024-04-02`');
$project->gen(10);

global $tester;
$tester->loadModel('programplan');

$planIdList = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchPairs('id', 'id');

$tester->programplan->config->edition = 'open';
r($tester->programplan->buildGanttLinks($planIdList)) && p() && e("0"); //禅道版本为开源版，检查任务关系。

$tester->programplan->config->edition = 'max';
r($tester->programplan->buildGanttLinks($planIdList)) && p() && e("0"); //禅道版本为旗舰版，检查任务关系。
