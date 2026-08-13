#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getByID();
timeout=0
cid=0

- 测试id为0的边界情况 @1
- 测试id为999不存在流水线 @1
- 测试id为负数边界情况 @1
- 测试id为7201获取流水线 属性id,engine @7201,jenkins
- 测试id为7201获取流水线 属性status @active

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('product')->gen(1);

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7201');
$pipeline->name->range('Pipeline 7201');
$pipeline->engine->range('jenkins');
$pipeline->repoID->range('0');
$pipeline->spaceID->range('1');
$pipeline->scope->range('space');
$pipeline->status->range('active');
$pipeline->createdBy->range('admin');
$pipeline->createdDate->setNull();
$pipeline->deleted->range('0');
$pipeline->gen(1);

su('admin');

$tester = new pipelineModelTest();

$result1 = $tester->getByIDTest(0);
$result2 = $tester->getByIDTest(999);
$result3 = $tester->getByIDTest(-1);
$result4 = $tester->getByIDTest(7201);

r((!is_object($result1) ? 1 : 0)) && p() && e('1');
r((!is_object($result2) ? 1 : 0)) && p() && e('1');
r((!is_object($result3) ? 1 : 0)) && p() && e('1');
r($result4) && p('id,engine') && e('7201,jenkins');
r($result4) && p('status') && e('active');
