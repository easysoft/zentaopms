#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('branch')->loadYaml('branch')->gen(10);
zenData('projectproduct')->loadYaml('projectproduct')->gen(30);
su('admin');

/**

title=测试 branchModel->getPairsByProjectProduct();
timeout=0
cid=15329

*/
$projectID = array(101, 102, 103, 104, 105);
$productID = array(5, 6, 7, 8, 9);

$branch = new branchTest();

r($branch->getPairsByProjectProductTest($projectID[0], $productID[0])) && p() && e('0:主干;');    // 测试获取 项目 101 产品 5 的关联分支
r($branch->getPairsByProjectProductTest($projectID[1], $productID[1])) && p() && e('1:分支1;');   // 测试获取 项目 102 产品 6 的关联分支
r($branch->getPairsByProjectProductTest($projectID[2], $productID[2])) && p() && e('4:分支4;');   // 测试获取 项目 103 产品 7 的关联分支
r($branch->getPairsByProjectProductTest($projectID[3], $productID[3])) && p() && e('7:分支7;');   // 测试获取 项目 104 产品 8 的关联分支
r($branch->getPairsByProjectProductTest($projectID[4], $productID[4])) && p() && e('10:分支10;'); // 测试获取 项目 105 产品 9 的关联分支
