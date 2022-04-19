#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->showBranch();
cid=1
pid=1

测试验证产品 1 是否显示分支标签 >> 2
测试验证产品 1 模块 1821 是否显示分支标签 >> 2
测试验证产品 1 执行 101 是否显示分支标签 >> 2
测试验证产品 1 模块 1821 执行 101 是否显示分支标签 >> 2
测试验证产品 41 是否显示分支标签 >> 1
测试验证产品 41 模块 1981 是否显示分支标签 >> 1
测试验证产品 41 执行 231 是否显示分支标签 >> 1
测试验证产品 41 模块 1981 执行 231 是否显示分支标签 >> 1
测试验证产品 81 是否显示分支标签 >> 1
测试验证产品 81 模块 2141 是否显示分支标签 >> 1
测试验证产品 81 执行 181 是否显示分支标签 >> 1
测试验证产品 81 模块 2141 执行 181 是否显示分支标签 >> 1

*/
$productID = array(1, 41, 81);
$moduleID  = array(0, 1821, 1981, 2141);
$executionID = array(0, 101, 231, 181);

$branch = new branchTest();

r($branch->showBranchTest($productID[0]))                                && p() && e('2'); // 测试验证产品 1 是否显示分支标签
r($branch->showBranchTest($productID[0], $moduleID[1]))                  && p() && e('2'); // 测试验证产品 1 模块 1821 是否显示分支标签
r($branch->showBranchTest($productID[0], $moduleID[0], $executionID[1])) && p() && e('2'); // 测试验证产品 1 执行 101 是否显示分支标签
r($branch->showBranchTest($productID[0], $moduleID[1], $executionID[1])) && p() && e('2'); // 测试验证产品 1 模块 1821 执行 101 是否显示分支标签
r($branch->showBranchTest($productID[1]))                                && p() && e('1'); // 测试验证产品 41 是否显示分支标签
r($branch->showBranchTest($productID[1], $moduleID[2]))                  && p() && e('1'); // 测试验证产品 41 模块 1981 是否显示分支标签
r($branch->showBranchTest($productID[1], $moduleID[0], $executionID[2])) && p() && e('1'); // 测试验证产品 41 执行 231 是否显示分支标签
r($branch->showBranchTest($productID[1], $moduleID[2], $executionID[2])) && p() && e('1'); // 测试验证产品 41 模块 1981 执行 231 是否显示分支标签
r($branch->showBranchTest($productID[2]))                                && p() && e('1'); // 测试验证产品 81 是否显示分支标签
r($branch->showBranchTest($productID[2], $moduleID[3]))                  && p() && e('1'); // 测试验证产品 81 模块 2141 是否显示分支标签
r($branch->showBranchTest($productID[2], $moduleID[0], $executionID[3])) && p() && e('1'); // 测试验证产品 81 执行 181 是否显示分支标签
r($branch->showBranchTest($productID[2], $moduleID[3], $executionID[3])) && p() && e('1'); // 测试验证产品 81 模块 2141 执行 181 是否显示分支标签