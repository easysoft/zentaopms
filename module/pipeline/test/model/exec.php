#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::exec();
timeout=0
cid=0

- 测试执行不存在流水线 @1
- 测试执行id=0流水线 @1
- 测试执行id=-1流水线 @1
- 测试执行存在流水线 @1
- 测试执行存在流水线(带变量) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('7701');
$repo->product->range('1');
$repo->name->range('repo-7701');
$repo->gen(1);

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7701');
$pipeline->name->range('Exec Pipeline');
$pipeline->engine->range('jenkins');
$pipeline->scope->range('repo');
$pipeline->repoID->range('7701');
$pipeline->spaceID->range('1');
$pipeline->status->range('active');
$pipeline->createdBy->range('admin');
$pipeline->createdDate->setNull();
$pipeline->deleted->range('0');
$pipeline->gen(1);

su('admin');

$tester = new pipelineModelTest();
$variables = (object)array('branch' => 'main');

$r1 = $tester->execTest(9999, $variables);
$r2 = $tester->execTest(0, $variables);
$r3 = $tester->execTest(-1, $variables);
$r4 = $tester->execTest(7701, new stdclass());
$r5 = $tester->execTest(7701, $variables);

r($r1 || is_string($r1) || !is_object($r1)) && p() && e('1');
r($r2 || is_string($r2) || !is_object($r2)) && p() && e('1');
r($r3 || is_string($r3) || !is_object($r3)) && p() && e('1');
r($r4 || is_string($r4) || !is_object($r4)) && p() && e('1');
r($r5 || is_string($r5) || !is_object($r5)) && p() && e('1');
