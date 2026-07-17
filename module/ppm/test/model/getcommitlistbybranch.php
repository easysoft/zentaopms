#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getCommitListByBranch();
timeout=0
cid=0

- 执行is_array($result) ? 1 : 0 @1
- 执行is_array($ppmModel->getCommitListByBranchTest($repoInfo, 'feature/bbb', 'release/main', $pager1)) ? 1 : 0 @1
- 执行is_array($ppmModel->getCommitListByBranchTest($repoInfo, 'feature/unknown', 'main', $pager1)) ? 1 : 0 @1
- 执行is_array($ppmModel->getCommitListByBranchTest($repoInfo, 'feature/bbb', 'main', $pager2)) ? 1 : 0 @1
- 执行count($result) @0

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

su('admin');

$ppmModel = new ppmModelTest();
$repoInfo = (object)array('id' => 42);
$pager1   = new pager(0, 20, 1);
$pager2   = new pager(0, 20, 2);
$result   = $ppmModel->getCommitListByBranchTest($repoInfo, 'feature/bbb', 'main', $pager1);

r(is_array($result) ? 1 : 0) && p() && e('1');
r(is_array($ppmModel->getCommitListByBranchTest($repoInfo, 'feature/bbb', 'release/main', $pager1)) ? 1 : 0) && p() && e('1');
r(is_array($ppmModel->getCommitListByBranchTest($repoInfo, 'feature/unknown', 'main', $pager1)) ? 1 : 0) && p() && e('1');
r(is_array($ppmModel->getCommitListByBranchTest($repoInfo, 'feature/bbb', 'main', $pager2)) ? 1 : 0) && p() && e('1');
r(count($result)) && p() && e('0');
