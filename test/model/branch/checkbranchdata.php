#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->checkBranchData();
cid=1
pid=1

测试验证branchID 1 的数据 >> 2
测试验证branchID 2 的数据 >> 2
测试验证branchID 3 的数据 >> 2
测试验证branchID 4 的数据 >> 2
测试验证branchID 5 的数据 >> 2
测试验证branchID 6 的数据 >> 2
测试验证branchID 1000001 的数据 >> 1

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