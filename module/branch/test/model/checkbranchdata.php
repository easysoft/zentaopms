#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
zenData('project')->loadYaml('execution')->gen(30);
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

title=测试 branchModel->checkBranchData();
timeout=0
cid=15321

- 测试验证branchID 1 的数据 @2
- 测试验证branchID 2 的数据 @2
- 测试验证branchID 3 的数据 @2
- 测试验证branchID 4 的数据 @2
- 测试验证branchID 5 的数据 @2
- 测试验证branchID 6 的数据 @2
- 测试验证branchID 1000001 的数据 @1

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
