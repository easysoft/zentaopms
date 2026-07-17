#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getPairs();
timeout=0
cid=0

- 测试repoID=7301获取流水线键值对 @1
- 测试repoID=7302获取流水线键值对 @1
- 测试repoID=0获取流水线键值对 >> 验证返回空 @0
- 测试repoID=9999获取流水线键值对 >> 验证返回空 @0
- 测试repoID=7301获取第1个值的name属性 @Pipeline 7301

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('product')->gen(1);

$repo1 = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo1->id->range('7301');
$repo1->product->range('1');
$repo1->name->range('repo-7301');
$repo1->gen(1);

$repo2 = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo2->id->range('7302');
$repo2->product->range('1');
$repo2->name->range('repo-7302');
$repo2->gen(1);

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7301-7302');
$pipeline->name->range('Pipeline 7301,Pipeline 7302');
$pipeline->engine->range('jenkins{2}');
$pipeline->scope->range('repo{2}');
$pipeline->repoID->range('7301,7302');
$pipeline->spaceID->range('1{2}');
$pipeline->status->range('active{2}');
$pipeline->createdBy->range('admin{2}');
$pipeline->createdDate->setNull();
$pipeline->deleted->range('0{2}');
$pipeline->gen(2);

su('admin');

$tester = new pipelineModelTest();

r(count($tester->getPairsTest(7301))) && p() && e('1');
r(count($tester->getPairsTest(7302))) && p() && e('1');
r(count($tester->getPairsTest(0))) && p() && e('0');
r(count($tester->getPairsTest(9999))) && p() && e('0');
r($tester->getPairsTest(7301)) && p('7301') && e('Pipeline 7301');
