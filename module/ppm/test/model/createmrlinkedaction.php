#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::createMRLinkedAction();
timeout=0
cid=0

- 执行ppmModel模块的createMRLinkedActionTest方法，参数是6501, 'linked2mr' @1
- 执行ppmModel模块的createMRLinkedActionTest方法，参数是6501, 'unlinked' @1
- 执行ppmModel模块的createMRLinkedActionTest方法，参数是9999, 'linked2mr' @1
- 执行ppmModel模块的createMRLinkedActionTest方法，参数是6501, 'linked2mr', '2026-07-10 09:00:00' @1
- 执行ppmModel模块的createMRLinkedActionTest方法，参数是0, 'linked2mr' @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(3);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6501');
$repo->product->range('1');
$repo->name->range('ppm-repo-6501');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6501');
$ppm->title->range('Test Link PPM 6501');
$ppm->repoID->range('6501');
$ppm->sourceRepoID->range('6501');
$ppm->sourceBranch->range('feature/action');
$ppm->targetRepoID->range('6501');
$ppm->targetBranch->range('master');
$ppm->status->range('opened');
$ppm->createdBy->range('admin');
$ppm->gen(1);

$story = zenData('story')->loadYaml('story', false, 2);
$story->id->range('8201');
$story->product->range('1');
$story->title->range('Story 8201');
$story->gen(1);

$relation = zenData('relation')->loadYaml('relation', false, 2);
$relation->id->range('9201');
$relation->AType->range('ppm');
$relation->AID->range('6501');
$relation->BType->range('story');
$relation->BID->range('8201');
$relation->relation->range('linkedfrom');
$relation->gen(1);

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->createMRLinkedActionTest(6501, 'linked2mr')) && p() && e('1');
r($ppmModel->createMRLinkedActionTest(6501, 'unlinked')) && p() && e('1');
r($ppmModel->createMRLinkedActionTest(9999, 'linked2mr')) && p() && e('1');
r($ppmModel->createMRLinkedActionTest(6501, 'linked2mr', '2026-07-10 09:00:00')) && p() && e('1');
r($ppmModel->createMRLinkedActionTest(0, 'linked2mr')) && p() && e('1');
