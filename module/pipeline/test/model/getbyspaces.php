#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getBySpaces();
timeout=0
cid=0

- 测试空spaceID列表 @0
- 测试单个spaceID @1
- 测试多个spaceID @1
- 测试不存在spaceID @0
- 测试不分spaceID的已删除流水线排除 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$pipeline = zenData('ops_pipeline')->loadYaml('pipeline', false, 2);
$pipeline->id->range('7401-7402');
$pipeline->name->range('Pipeline 7401,Pipeline 7402');
$pipeline->engine->range('jenkins{2}');
$pipeline->scope->range('space{2}');
$pipeline->repoID->range('0{2}');
$pipeline->spaceID->range('101,102');
$pipeline->status->range('active{2}');
$pipeline->createdBy->range('admin{2}');
$pipeline->createdDate->setNull();
$pipeline->editedDate->setNull();
$pipeline->lastExec->setNull();
$pipeline->deleted->range('0,0');
$pipeline->gen(2);

$tester = new pipelineModelTest();

r(count($tester->getBySpacesTest(array()))) && p() && e('0');
r(count($tester->getBySpacesTest(array(101)))) && p() && e('1');
r(count($tester->getBySpacesTest(array(101, 102)))) && p() && e('2');
r(count($tester->getBySpacesTest(array(99999)))) && p() && e('0');
r($tester->getBySpacesTest(array(101))) && p('7401:spaceID') && e('101');
