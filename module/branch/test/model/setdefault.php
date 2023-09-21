#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->setDefault();
timeout=0
cid=1

*/

$productIdList = array(0, 6);
$branchIdList  = array(0, 1);

$branch = new branchTest();

r($branch->setDefaultTest($productIdList[0], $branchIdList[0])) && p()               && e('0');  // 测试将productID 0, branchID 0的分支设为默认分支
r($branch->setDefaultTest($productIdList[0], $branchIdList[1])) && p('name,default') && e('分支1,1');  // 测试将productID 0, branchID 1的分支设为默认分支
r($branch->setDefaultTest($productIdList[1], $branchIdList[0])) && p()               && e('主干');  // 测试将productID 41, branchID 0的分支设为默认分支
r($branch->setDefaultTest($productIdList[1], $branchIdList[1])) && p('name,default') && e('分支1,1');  // 测试将productID 41, branchID 0的分支设为默认分支
