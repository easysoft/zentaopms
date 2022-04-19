#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->getPairsByProjectProduct();
cid=1
pid=1

测试获取 项目 51 产品 41 的关联分支 >> 1:分支1;
测试获取 项目 52 产品 42 的关联分支 >> 3:分支3;
测试获取 项目 53 产品 43 的关联分支 >> 5:分支5;
测试获取 项目 54 产品 44 的关联分支 >> 7:分支7;
测试获取 项目 55 产品 45 的关联分支 >> 9:分支9;

*/
$projectID = array(51, 52, 53, 54, 55);
$productID = array(41, 42, 43, 44, 45);

$branch = new branchTest();

r($branch->getPairsByProjectProductTest($projectID[0], $productID[0])) && p() && e('1:分支1;'); // 测试获取 项目 51 产品 41 的关联分支
r($branch->getPairsByProjectProductTest($projectID[1], $productID[1])) && p() && e('3:分支3;'); // 测试获取 项目 52 产品 42 的关联分支
r($branch->getPairsByProjectProductTest($projectID[2], $productID[2])) && p() && e('5:分支5;'); // 测试获取 项目 53 产品 43 的关联分支
r($branch->getPairsByProjectProductTest($projectID[3], $productID[3])) && p() && e('7:分支7;'); // 测试获取 项目 54 产品 44 的关联分支
r($branch->getPairsByProjectProductTest($projectID[4], $productID[4])) && p() && e('9:分支9;'); // 测试获取 项目 55 产品 45 的关联分支