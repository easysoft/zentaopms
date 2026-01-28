#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(30);
zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
zenData('productplan')->loadYaml('productplan')->gen(30);
zenData('release')->loadYaml('release')->gen(30);
zenData('build')->loadYaml('build')->gen(30);
zenData('story')->loadYaml('story')->gen(30);
zenData('user')->gen(5);
zenData('module')->gen(0);
zenData('bug')->gen(0);
zenData('case')->gen(0);
zenData('projectstory')->gen(0);
su('admin');

/**

title=测试 branchModel->afterMerge();
timeout=0
cid=15341

- 测试合并分支1 到 分支2 @0
- 测试合并分支4 到 新建分支 @1
- 测试合并分支6 到 分支2 @0
- 测试合并分支6 到 分支2 @0
- 测试合并分支6 到 主干 @1

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

$branchTester = new branchTaoTest();
r($branchTester->afterMergeTest($productID[0], $mergedBranches[0], $mergeBranch1)) && p() && e('0'); // 测试合并分支1 到 分支2
r($branchTester->afterMergeTest($productID[0], $mergedBranches[1], $mergeBranch2)) && p() && e('1'); // 测试合并分支4 到 新建分支
r($branchTester->afterMergeTest($productID[1], $mergedBranches[0], $mergeBranch1)) && p() && e('0'); // 测试合并分支6 到 分支2
r($branchTester->afterMergeTest($productID[1], $mergedBranches[1], $mergeBranch1)) && p() && e('0'); // 测试合并分支6 到 分支2
r($branchTester->afterMergeTest($productID[1], $mergedBranches[2], $mergeBranch3)) && p() && e('1'); // 测试合并分支6 到 主干