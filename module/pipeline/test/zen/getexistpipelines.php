#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::getExistPipelines();
timeout=0
cid=0

- 测试getExistPipelines(repoID=0)按空间分组 @1
- 测试getExistPipelines(repoID=7401)按版本库分组 @1
- 测试getExistPipelines(repoID=999)不存在版本库 @1
- 测试getExistPipelines(参数)返回数组 @1
- 测试getExistPipelines(重复调用) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(2);
su('admin');

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7401');
$pipeline->name->range('Pipeline 7401');
$pipeline->engine->range('jenkins');
$pipeline->scope->range('repo');
$pipeline->repoID->range('7401');
$pipeline->spaceID->range('1');
$pipeline->status->range('active');
$pipeline->createdBy->range('admin');
$pipeline->createdDate->setNull();
$pipeline->editedDate->setNull();
$pipeline->lastExec->setNull();
$pipeline->deleted->range('0');
$pipeline->gen(1);

$tester = new pipelineZenTest();

$r1 = $tester->getExistPipelinesTest(0);
$r2 = $tester->getExistPipelinesTest(7401);
$r3 = $tester->getExistPipelinesTest(999);
$r4 = $tester->getExistPipelinesTest(0);
$r5 = $tester->getExistPipelinesTest(0);

r(is_array($r1) ? '1' : '0') && p() && e('1');
r(is_array($r2) ? '1' : '0') && p() && e('1');
r(is_array($r3) ? '1' : '0') && p() && e('1');
r(is_array($r4) ? '1' : '0') && p() && e('1');
r(is_array($r5) ? '1' : '0') && p() && e('1');
