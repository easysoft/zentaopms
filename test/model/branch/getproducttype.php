#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->getProductType();
cid=1
pid=1

测试获取分支 1 的项目类型 >> branch
测试获取分支 3 的项目类型 >> branch
测试获取分支 81 的项目类型 >> platform
测试获取分支 83 的项目类型 >> platform
测试获取分支 161 的项目类型 >> normal
测试获取分支 163 的项目类型 >> normal

*/
$branchID = array(1, 3, 81, 83, 161, 163);

$branch = new branchTest();

r($branch->getProductTypeTest($branchID[0])) && p() && e('branch');   // 测试获取分支 1 的项目类型
r($branch->getProductTypeTest($branchID[1])) && p() && e('branch');   // 测试获取分支 3 的项目类型
r($branch->getProductTypeTest($branchID[2])) && p() && e('platform'); // 测试获取分支 81 的项目类型
r($branch->getProductTypeTest($branchID[3])) && p() && e('platform'); // 测试获取分支 83 的项目类型
r($branch->getProductTypeTest($branchID[4])) && p() && e('normal');   // 测试获取分支 161 的项目类型
r($branch->getProductTypeTest($branchID[5])) && p() && e('normal');   // 测试获取分支 163 的项目类型
