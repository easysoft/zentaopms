#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::linkObjects();
timeout=0
cid=0

- 执行$beforeCount @3
- 执行ppmModel模块的linkObjectsTest方法，参数是$ppmForLinking  @1
- 执行$ppmModel->instance->dao->select('count(*)')->from(TABLE_RELATION)->where('AType')->eq('ppm')->andWhere('AID')->eq(6201)->fetch('count(*)') - $beforeCount @0
- 执行ppmModel模块的linkObjectsTest方法，参数是$ppmForLinking  @1
- 执行$ppmModel->instance->dao->select('count(*)')->from(TABLE_RELATION)->where('AType')->eq('ppm')->andWhere('AID')->eq(6201)->fetch('count(*)') - $beforeCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(3);
zenData('product')->gen(1);

$stories = zenData('story');
$stories->id->range('1-3');
$stories->title->range('Story 1,Story 2,Story 3');
$stories->status->range('active{3}');
$stories->deleted->range('0{3}');
$stories->gen(3);

$tasks = zenData('task');
$tasks->id->range('1-3');
$tasks->name->range('Task 1,Task 2,Task 3');
$tasks->status->range('wait{3}');
$tasks->deleted->range('0{3}');
$tasks->gen(3);

$bugs = zenData('bug');
$bugs->id->range('1-3');
$bugs->title->range('Bug 1,Bug 2,Bug 3');
$bugs->status->range('active{3}');
$bugs->deleted->range('0{3}');
$bugs->gen(3);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6201');
$repo->product->range('1');
$repo->name->range('ppm-repo-6201');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6201');
$ppm->title->range('Link PPM 6201');
$ppm->repoID->range('6201');
$ppm->sourceRepoID->range('6201');
$ppm->sourceBranch->range('feature/link');
$ppm->targetRepoID->range('6201');
$ppm->targetBranch->range('master');
$ppm->status->range('opened');
$ppm->createdBy->range('admin');
$ppm->createdDate->range('`2026-07-10 09:00:00`');
$ppm->gen(1);

$relation = zenData('relation');
$relation->id->range('1-3');
$relation->product->range('1{3}');
$relation->relation->range('interrated{3}');
$relation->AType->range('ppm{3}');
$relation->AID->range('6201{3}');
$relation->BType->range('story,task,bug');
$relation->BID->range('1,2,3');
$relation->gen(3);

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

$ppmModel      = new ppmModelTest();
$beforeCount   = (int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_RELATION)->where('AType')->eq('ppm')->andWhere('AID')->eq(6201)->fetch('count(*)');
$ppmForLinking = (object)array('id' => 6201, 'repoID' => 6201, 'targetRepoID' => 6201, 'createdBy' => 'admin', 'createdDate' => '2026-07-10 09:00:00');

r($beforeCount) && p() && e('3');
r($ppmModel->linkObjectsTest($ppmForLinking)) && p() && e('1');
r((int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_RELATION)->where('AType')->eq('ppm')->andWhere('AID')->eq(6201)->fetch('count(*)') - $beforeCount) && p() && e('0');
r($ppmModel->linkObjectsTest($ppmForLinking)) && p() && e('1');
r((int)$ppmModel->instance->dao->select('count(*)')->from(TABLE_RELATION)->where('AType')->eq('ppm')->andWhere('AID')->eq(6201)->fetch('count(*)') - $beforeCount) && p() && e('0');