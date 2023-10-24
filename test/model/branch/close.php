#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->close();
cid=1
pid=1

测试关闭branchID 1 >> 1,closed
测试关闭branchID 2 >> 2,closed
测试关闭branchID 3 >> 3,closed
测试关闭branchID 4 >> 4,closed
测试关闭branchID 5 >> 5,closed
测试关闭branchID 6 >> 6,closed
测试重复关闭branchID 1 >> 1,closed

*/
$branchID = array(1, 2, 3, 4, 5, 6);

$branch = new branchTest();

r($branch->closeTest($branchID[0])) && p('id,status') && e('1,closed'); // 测试关闭branchID 1
r($branch->closeTest($branchID[1])) && p('id,status') && e('2,closed'); // 测试关闭branchID 2
r($branch->closeTest($branchID[2])) && p('id,status') && e('3,closed'); // 测试关闭branchID 3
r($branch->closeTest($branchID[3])) && p('id,status') && e('4,closed'); // 测试关闭branchID 4
r($branch->closeTest($branchID[4])) && p('id,status') && e('5,closed'); // 测试关闭branchID 5
r($branch->closeTest($branchID[5])) && p('id,status') && e('6,closed'); // 测试关闭branchID 6
r($branch->closeTest($branchID[0])) && p('id,status') && e('1,closed'); // 测试重复关闭branchID 1
