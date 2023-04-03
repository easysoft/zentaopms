#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->unlinkBranch4Project();
cid=1
pid=1

测试产品 41 转为普通产品接触分支关联 >> 0
测试产品 42 转为普通产品接触分支关联 >> 0
测试产品 43 转为普通产品接触分支关联 >> 0
测试产品 44 45 转为普通产品接触分支关联 >> 0

*/
$productIDList = array('41', '42', '43', '44,45');

$branch = new branchTest();

r($branch->unlinkBranch4ProjectTest($productIDList[0])) && p() && e('0'); // 测试产品 41 转为普通产品接触分支关联
r($branch->unlinkBranch4ProjectTest($productIDList[1])) && p() && e('0'); // 测试产品 42 转为普通产品接触分支关联
r($branch->unlinkBranch4ProjectTest($productIDList[2])) && p() && e('0'); // 测试产品 43 转为普通产品接触分支关联
r($branch->unlinkBranch4ProjectTest($productIDList[3])) && p() && e('0'); // 测试产品 44 45 转为普通产品接触分支关联
