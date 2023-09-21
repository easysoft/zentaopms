#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(20);
zdTable('branch')->config('branch')->gen(30);
su('admin');

/**

title=测试 branchModel->getProductType();
timeout=0
cid=1

*/
$branchID = array(1, 17, 26);
$branch = new branchTest();

r($branch->getProductTypeTest($branchID[0])) && p() && e('branch');   // 测试获取分支 1 的项目类型
r($branch->getProductTypeTest($branchID[1])) && p() && e('platform'); // 测试获取分支 81 的项目类型
r($branch->getProductTypeTest($branchID[2])) && p() && e('normal');   // 测试获取分支 161 的项目类型
