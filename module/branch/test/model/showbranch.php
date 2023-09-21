#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
zdTable('project')->config('execution')->gen(20);
zdTable('module')->gen(100);
su('admin');

/**

title=测试 branchModel->showBranch();
timeout=0
cid=1

*/
$productID   = array(1, 6, 100);
$moduleID    = array(0, 18, 19, 21);
$executionID = array(0, 101, 231, 181);

$branch = new branchTest();

r($branch->showBranchTest($productID[0]))                                && p() && e('2'); // 测试验证产品 1 是否显示分支标签
r($branch->showBranchTest($productID[0], $moduleID[1]))                  && p() && e('2'); // 测试验证产品 1 是否显示分支标签
r($branch->showBranchTest($productID[0], $moduleID[0], $executionID[1])) && p() && e('2'); // 测试验证产品 1 是否显示分支标签
r($branch->showBranchTest($productID[0], $moduleID[1], $executionID[1])) && p() && e('2'); // 测试验证产品 1 是否显示分支标签
r($branch->showBranchTest($productID[1]))                                && p() && e('1'); // 测试验证产品 6 是否显示分支标签
r($branch->showBranchTest($productID[1], $moduleID[2]))                  && p() && e('1'); // 测试验证产品 6 是否显示分支标签
r($branch->showBranchTest($productID[1], $moduleID[0], $executionID[2])) && p() && e('1'); // 测试验证产品 6 是否显示分支标签
r($branch->showBranchTest($productID[1], $moduleID[2], $executionID[2])) && p() && e('1'); // 测试验证产品 6 是否显示分支标签
r($branch->showBranchTest($productID[2]))                                && p() && e('2'); // 测试验证产品 100 是否显示分支标签
r($branch->showBranchTest($productID[2], $moduleID[3]))                  && p() && e('2'); // 测试验证产品 100 是否显示分支标签
r($branch->showBranchTest($productID[2], $moduleID[0], $executionID[3])) && p() && e('2'); // 测试验证产品 100 是否显示分支标签
r($branch->showBranchTest($productID[2], $moduleID[3], $executionID[3])) && p() && e('2'); // 测试验证产品 100 是否显示分支标签
