#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::close();
timeout=0
cid=0

- 执行ppmModel模块的closeTest方法，参数是6301 @1
- 执行instance模块的fetchByID方法，参数是6301 属性status @closed
- 执行ppmModel模块的closeTest方法，参数是6302 属性status @closed
- 执行instance模块的fetchByID方法，参数是6302 @1
- 执行ppmModel模块的closeTest方法，参数是9999 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('6301');
$repo->product->range('1');
$repo->name->range('ppm-repo-6301');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('6301-6302');
$ppm->title->range('Review PPM 6301,Review PPM 6302');
$ppm->repoID->range('6301{2}');
$ppm->sourceRepoID->range('6301{2}');
$ppm->sourceBranch->range('feature/review,feature/closed');
$ppm->targetRepoID->range('6301{2}');
$ppm->targetBranch->range('release/main,release/main');
$ppm->status->range('opened,opened');
$ppm->createdBy->range('admin,admin');
$ppm->gen(2);

su('admin');

$ppmModel = new ppmModelTest();

r($ppmModel->closeTest(6301)) && p() && e('1');
r($ppmModel->instance->fetchByID(6301)) && p('status') && e('closed');
r($ppmModel->instance->fetchByID(6302)) && p('status') && e('opened');
r($ppmModel->closeTest(6302)) && p() && e('1');
r($ppmModel->instance->fetchByID(6302)) && p('status') && e('closed');
