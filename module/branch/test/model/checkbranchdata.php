#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
zdTable('project')->config('execution')->gen(30);
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

title=测试 branchModel->checkBranchData();
timeout=0
cid=1

*/
$branchID = array(1, 2, 3, 4, 5, 6, 1000001);

$branch = new branchTest();

r($branch->checkBranchDataTest($branchID[0])) && p() && e('2'); // 测试验证branchID 1 的数据
r($branch->checkBranchDataTest($branchID[1])) && p() && e('2'); // 测试验证branchID 2 的数据
r($branch->checkBranchDataTest($branchID[2])) && p() && e('2'); // 测试验证branchID 3 的数据
r($branch->checkBranchDataTest($branchID[3])) && p() && e('2'); // 测试验证branchID 4 的数据
r($branch->checkBranchDataTest($branchID[4])) && p() && e('2'); // 测试验证branchID 5 的数据
r($branch->checkBranchDataTest($branchID[5])) && p() && e('2'); // 测试验证branchID 6 的数据
r($branch->checkBranchDataTest($branchID[6])) && p() && e('1'); // 测试验证branchID 1000001 的数据
