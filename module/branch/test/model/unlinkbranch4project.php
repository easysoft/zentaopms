#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('projectproduct')->config('projectproduct')->gen(30);
zdTable('user')->gen(5);
su('admin');

/**

title=测试 branchModel->unlinkBranch4Project();
timeout=0
cid=1

*/

$productIdList[0] = array(6);
$productIdList[1] = array(7, 8);
$productIdList[2] = array(9);

$branchTester = new branchTest();
r($branchTester->unlinkBranch4ProjectTest($productIdList[0])) && p() && e('0'); // 测试产品 6  转为普通产品接触分支关联
r($branchTester->unlinkBranch4ProjectTest($productIdList[1])) && p() && e('0'); // 测试产品 7,8 转为普通产品接触分支关联
r($branchTester->unlinkBranch4ProjectTest($productIdList[2])) && p() && e('0'); // 测试产品 9 转为普通产品接触分支关联
