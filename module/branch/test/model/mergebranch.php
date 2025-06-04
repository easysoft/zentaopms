#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
zenData('user')->gen(5);
su('admin');

/**

title=测试 branchModel->mergeBranch();
timeout=1
cid=1

- 测试合并分支 1 到 分支 2 @2
- 测试合并分支 2 到 新建分支 @3
- 测试合并分支 3 4 到 主干 @1

*/
$productID      = array(6, 7);
$mergedBranches = array(1, 4, 6);

$mergeBranch1 = new stdclass();
$mergeBranch1->createBranch       = 0;
$mergeBranch1->mergedBranchIDList = array(1);
$mergeBranch1->targetBranch       = 2;

$mergeBranch2 = new stdclass();
$mergeBranch2->name               = '新建合并的分支';
$mergeBranch2->desc               = '合并分支的描述';
$mergeBranch2->createBranch       = 1;
$mergeBranch2->mergedBranchIDList = array(2);
$mergeBranch2->targetBranch       = 0;

$mergeBranch3 = new stdclass();
$mergeBranch3->createBranch       = 0;
$mergeBranch3->mergedBranchIDList = array(4, 5);
$mergeBranch3->targetBranch       = 0;

$branch = new branchTest();
r($branch->mergeBranchTest($productID[0], $mergedBranches[0], $mergeBranch1)) && p() && e('2'); // 测试合并分支 1 到 分支 2
r($branch->mergeBranchTest($productID[0], $mergedBranches[1], $mergeBranch2)) && p() && e('3'); // 测试合并分支 2 到 新建分支
r($branch->mergeBranchTest($productID[1], $mergedBranches[2], $mergeBranch3)) && p() && e('1'); // 测试合并分支 3 4 到 主干
