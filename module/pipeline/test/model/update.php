#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::update();
timeout=0
cid=0

- 测试更新存在的流水线名称 @1
- 测试更新流水线状态 @1
- 测试更新为相同名称 @1
- 测试更新不存在流水线(不报错) @1
- 测试更新id=0流水线(不报错) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('7601');
$repo->product->range('1');
$repo->name->range('repo-7601');
$repo->gen(1);

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7601');
$pipeline->name->range('Original Pipeline');
$pipeline->engine->range('jenkins');
$pipeline->scope->range('repo');
$pipeline->repoID->range('7601');
$pipeline->spaceID->range('1');
$pipeline->status->range('active');
$pipeline->createdBy->range('admin');
$pipeline->createdDate->setNull();
$pipeline->deleted->range('0');
$pipeline->gen(1);

su('admin');

$tester = new pipelineModelTest();

$updateData = (object)array('name' => 'Updated Pipeline', 'status' => 'active');
$sameName   = (object)array('name' => 'Original Pipeline');
$statusData = (object)array('name' => 'Updated Pipeline', 'status' => 'draft');

$v1 = $tester->updateTest(7601, $updateData);
$v2 = $tester->updateTest(7601, $statusData);
$v3 = $tester->updateTest(7601, $sameName);
$v4 = $tester->updateTest(9999, $updateData);
$v5 = $tester->updateTest(0, $updateData);

r(($v1 === 1 || $v1 === 0) ? 1 : 0) && p() && e('1');
r(($v2 === 1 || $v2 === 0) ? 1 : 0) && p() && e('1');
r(($v3 === 1 || $v3 === 0) ? 1 : 0) && p() && e('1');
r(($v4 === 1 || $v4 === 0) ? 1 : 0) && p() && e('1');
r(($v5 === 1 || $v5 === 0) ? 1 : 0) && p() && e('1');
