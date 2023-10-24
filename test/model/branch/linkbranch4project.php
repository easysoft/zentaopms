#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->linkBranch4Project();
cid=1
pid=1

测试关联产品 41 的分支到项目 >> 4
测试关联产品 42 的分支到项目 >> 4
测试关联产品 43 的分支到项目 >> 4
测试关联产品 44 的分支到项目 >> 4
测试关联产品 45 的分支到项目 >> 4

*/

$productID = array(41, 42, 43, 44, 45);

$branch = new branchTest();

r($branch->linkBranch4ProjectTest($productID[0])) && p() && e('4'); // 测试关联产品 41 的分支到项目
r($branch->linkBranch4ProjectTest($productID[1])) && p() && e('4'); // 测试关联产品 42 的分支到项目
r($branch->linkBranch4ProjectTest($productID[2])) && p() && e('4'); // 测试关联产品 43 的分支到项目
r($branch->linkBranch4ProjectTest($productID[3])) && p() && e('4'); // 测试关联产品 44 的分支到项目
r($branch->linkBranch4ProjectTest($productID[4])) && p() && e('4'); // 测试关联产品 45 的分支到项目
