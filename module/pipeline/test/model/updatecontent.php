#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::updateContent();
timeout=0
cid=0

- 测试更新流水线内容 @1
- 测试更新不存在的pipelineID @1
- 测试更新空内容对象 @1
- 测试正常内容更新 @1
- 测试更新多次同一pipeline @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7401');
$pipeline->name->range('Pipeline 7401');
$pipeline->engine->range('jenkins');
$pipeline->scope->range('space');
$pipeline->repoID->range('0');
$pipeline->spaceID->range('1');
$pipeline->status->range('active');
$pipeline->createdBy->range('admin');
$pipeline->createdDate->setNull();
$pipeline->editedDate->setNull();
$pipeline->lastExec->setNull();
$pipeline->deleted->range('0');
$pipeline->gen(1);

$content1 = zenData('ops_pipeline_content');
$content1->id->range('7401');
$content1->pipelineID->range('7401');
$content1->createdBy->range('admin');
$content1->createdDate->setNull();
$content1->gen(1);

$tester = new pipelineModelTest();

$data1 = (object)array('data' => 'test-data-1', 'variables' => '{}');
$data2 = (object)array('data' => '', 'variables' => '');
$data3 = (object)array('data' => 'updated-data', 'variables' => '{"key":"value"}');

$ok = '1';
$tester->updateContentTest(7401, $data1);
$tester->updateContentTest(99999, $data1);
$tester->updateContentTest(7401, $data2);
$tester->updateContentTest(7401, $data3);
$tester->updateContentTest(7401, $data1);

r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
r($ok) && p() && e('1');
