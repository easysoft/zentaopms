#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getExecutionByPipeline();
timeout=0
cid=0

- 测试空流水线ID列表(showLast=false) @0
- 测试空流水线ID列表(showLast=true) @0
- 测试正常查询执行记录(showLast=false返回2条) @2
- 测试showLast=true获取最新执行(返回1条) @1
- 测试不存在的流水线ID @0

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
$exec->id->range('7401-7402');
$exec->pipelineID->range('7401,7401');
$exec->status->range('created,done');
$exec->createdBy->range('admin,admin');
$exec->createdDate->range('[2025-01-01 10:00:00],[2025-06-01 12:00:00]');
$exec->gen(2);

$tester = new pipelineModelTest();

r(count($tester->getExecutionByPipelineTest(array()))) && p() && e('0');
r(count($tester->getExecutionByPipelineTest(array(), true))) && p() && e('0');
r(count($tester->getExecutionByPipelineTest(array(7401)))) && p() && e('2');
r(count($tester->getExecutionByPipelineTest(array(7401), true))) && p() && e('1');
r(count($tester->getExecutionByPipelineTest(array(99999)))) && p() && e('0');
