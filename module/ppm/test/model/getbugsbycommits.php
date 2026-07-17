#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getBugsByCommits();
timeout=0
cid=0

- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 81  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 81, $pager  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 9999  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是9999, 81  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 81  @1
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是0, 81  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 0  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是0, 0  @0
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 81, $pager  @1
- 执行ppmModel模块的getBugsByCommitsTest方法，参数是42, 81  @0

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
$repo->defaultBranch->range('main');
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

$bug = zenData('bug');
$bug->id->range('8101');
$bug->title->range('Bug 8101');
$bug->status->range('active');
$bug->repo->range('42');
$bug->v2->range('6bada47137f46e3b6b3792b397b714e3726e6990');
$bug->deleted->range('0');
$bug->gen(1);

su('admin');

$ppmModel = new ppmModelTest();
$pager    = new pager(0, 20, 1);

r($ppmModel->getBugsByCommitsTest(42, 81)) && p() && e('0');
r($ppmModel->getBugsByCommitsTest(42, 81, $pager)) && p() && e('0');
r($ppmModel->getBugsByCommitsTest(42, 9999)) && p() && e('0');
r($ppmModel->getBugsByCommitsTest(9999, 81)) && p() && e('0');
r(is_array($ppmModel->getBugsByCommitsTest(42, 81))) && p() && e('1');
r($ppmModel->getBugsByCommitsTest(0, 81)) && p() && e('0');
r($ppmModel->getBugsByCommitsTest(42, 0)) && p() && e('0');
r($ppmModel->getBugsByCommitsTest(0, 0)) && p() && e('0');
r(is_array($ppmModel->getBugsByCommitsTest(42, 81, $pager))) && p() && e('1');
r(count($ppmModel->getBugsByCommitsTest(42, 81))) && p() && e('0');