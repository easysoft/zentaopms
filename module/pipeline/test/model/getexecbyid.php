#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getExecByID();
timeout=0
cid=0

- 测试execID=0边界情况 @0
- 测试execID=999不存在流水线 @0
- 测试execID=7401获取执行属性id,status @7401,created
- 测试execID=7401获取执行属性trigger @manual
- 测试execID=负数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$pipeline = zenData('ops_pipeline');
$pipeline->id->range('7401');
$pipeline->name->range('Pipeline 7401');
$pipeline->engine->range('jenkins');
$pipeline->scope->range('space');
$pipeline->repoID->range('0');
$pipeline->spaceID->range('1');
$pipeline->status->range('active');
$pipeline->createdBy->range('admin');
$pipeline->deleted->range('0');
$pipeline->gen(1);

$exec = zenData('ops_pipeline_executions');
$exec->id->range('7401');
$exec->pipelineID->range('7401');
$exec->status->range('created');
$exec->trigger->range('manual');
$exec->createdBy->range('admin');
$exec->createdDate->range('[2025-01-01 10:00:00]');
$exec->gen(1);

$tester = new pipelineModelTest();

r($tester->getExecByIDTest(0) ? 1 : 0) && p() && e('0');
r($tester->getExecByIDTest(999) ? 1 : 0) && p() && e('0');
r($tester->getExecByIDTest(7401)) && p('id,status') && e('7401,created');
r($tester->getExecByIDTest(7401)) && p('trigger') && e('manual');
r($tester->getExecByIDTest(-1) ? 1 : 0) && p() && e('0');
