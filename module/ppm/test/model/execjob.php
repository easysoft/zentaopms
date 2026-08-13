#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::execJob();
timeout=0
cid=0

- 执行ppmModel模块的execJobTest方法，参数是0, 1 @0
- 执行ppmModel模块的execJobTest方法，参数是6601, 0 @0
- 执行ppmModel模块的execJobTest方法，参数是9999, 1 @0
- 执行ppmModel模块的execJobTest方法，参数是0, 0 @0
- 执行ppmModel模块的execJobTest方法，参数是9999, 9999 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(3);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6601');
$repo->product->range('1');
$repo->name->range('ppm-repo-6601');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6601');
$ppm->title->range('Test Job PPM 6601');
$ppm->repoID->range('6601');
$ppm->sourceRepoID->range('6601');
$ppm->sourceBranch->range('feature/job');
$ppm->targetRepoID->range('6601');
$ppm->targetBranch->range('master');
$ppm->status->range('opened');
$ppm->createdBy->range('admin');
$ppm->gen(1);

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->execJobTest(0, 1)) && p() && e('0');
r($ppmModel->execJobTest(6601, 0)) && p() && e('0');
r($ppmModel->execJobTest(9999, 1)) && p() && e('0');
r($ppmModel->execJobTest(0, 0)) && p() && e('0');
r($ppmModel->execJobTest(9999, 9999)) && p() && e('0');
