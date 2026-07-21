#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::apiGetMRCommits();
timeout=0
cid=0

- 执行ppmModel模块的apiGetMRCommitsTest方法，参数是42, 81  @0
- 执行is_array($ppmModel->apiGetMRCommitsTest(42, 81, $pager1)) ? 1 : 0 @1
- 执行$pager1->recTotal @0
- 执行is_array($ppmModel->apiGetMRCommitsTest(42, 81, $pager2)) ? 1 : 0 @1
- 执行$pager2->recTotal @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->loadClass('pager', true);
$app->rawModule = 'ppm';
$app->rawMethod = 'browse';
$app->setMethodName('browse');

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('user')->gen(3);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('42');
$repo->product->range('1');
$repo->name->range('ppm-repo-42');
$repo->gen(1);

$ppm = zenData('ops_ppm')->loadYaml('ops_ppm', false, 2);
$ppm->id->range('81');
$ppm->title->range('API PPM 81');
$ppm->repoID->range('42');
$ppm->sourceRepoID->range('42');
$ppm->sourceBranch->range('feature/bbb');
$ppm->sourceSHA->range('6bada47137f46e3b6b3792b397b714e3726e6990');
$ppm->targetRepoID->range('42');
$ppm->targetBranch->range('main');
$ppm->mergeSHA->range('4444444444444444444444444444444444444444');
$ppm->status->range('opened');
$ppm->createdBy->range('admin');
$ppm->gen(1);

su('admin');

$ppmModel = new ppmModelTest();
$pager1   = new pager(0, 20, 1);
$pager2   = new pager(0, 20, 2);

r(count((array)$ppmModel->apiGetMRCommitsTest(42, 81))) && p() && e('0');
r(is_array($ppmModel->apiGetMRCommitsTest(42, 81, $pager1)) ? 1 : 0) && p() && e('1');
r($pager1->recTotal) && p() && e('0');
r(is_array($ppmModel->apiGetMRCommitsTest(42, 81, $pager2)) ? 1 : 0) && p() && e('1');
r($pager2->recTotal) && p() && e('0');
