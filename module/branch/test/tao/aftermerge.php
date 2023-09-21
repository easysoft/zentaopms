#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(30);
zdTable('projectproduct')->config('projectproduct')->gen(30);
zdTable('productplan')->config('productplan')->gen(30);
zdTable('release')->config('release')->gen(30);
zdTable('build')->config('build')->gen(30);
zdTable('story')->config('story')->gen(30);
zdTable('user')->gen(5);
zdTable('module')->gen(0);
zdTable('bug')->gen(0);
zdTable('case')->gen(0);
zdTable('projectstory')->gen(0);
su('admin');

/**

title=测试 branchModel->afterMerge();
timeout=0
cid=1

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

$branchTester = new branchTest();
r($branchTester->afterMergeTest($productID[0], $mergedBranches[0], $mergeBranch1)) && p() && e('0'); // 测试合并分支1 到 分支2
r($branchTester->afterMergeTest($productID[0], $mergedBranches[1], $mergeBranch2)) && p() && e('1'); // 测试合并分支4 到 新建分支
r($branchTester->afterMergeTest($productID[1], $mergedBranches[2], $mergeBranch3)) && p() && e('1'); // 测试合并分支6 到 主干
