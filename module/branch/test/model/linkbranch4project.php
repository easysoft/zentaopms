#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('projectstory')->gen(0);
zdTable('bug')->gen(30);
zdTable('case')->gen(30);
zdTable('projectproduct')->config('projectproduct')->gen(10);
zdTable('product')->config('product')->gen(10);
zdTable('user')->gen(5);
su('admin');

/**

title=测试 branchModel->linkBranch4Project();
timeout=0
cid=1

*/

$productIdList = array(1, 2, 3);

$branchTester = new branchTest();
r($branchTester->linkBranch4ProjectTest($productIdList[0])) && p() && e('0'); // 测试产品1关联项目后，项目关联的分支是否正确
r($branchTester->linkBranch4ProjectTest($productIdList[1])) && p() && e('0'); // 测试产品2关联项目后，项目关联的分支是否正确
r($branchTester->linkBranch4ProjectTest($productIdList[2])) && p() && e('0'); // 测试产品3关联项目后，项目关联的分支是否正确
r($branchTester->linkBranch4ProjectTest($productIdList))    && p() && e('0'); // 测试产品1、产品2、产品3关联项目后，项目关联的分支是否正确
