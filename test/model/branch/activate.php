#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->activate();
cid=1
pid=1

测试激活分支 2 >> 2,active
测试激活分支 4 >> 4,active
测试激活分支 6 >> 6,active
测试激活分支 8 >> 8,active
测试激活分支 10 >> 10,active
测试激活分支 1 >> 1,active

*/

$branchID = array(2, 4, 6, 8, 10, 1);

$branch = new branchTest();

r($branch->activateTest($branchID[0])) && p('id,status') && e('2,active');  // 测试激活分支 2
r($branch->activateTest($branchID[1])) && p('id,status') && e('4,active');  // 测试激活分支 4
r($branch->activateTest($branchID[2])) && p('id,status') && e('6,active');  // 测试激活分支 6
r($branch->activateTest($branchID[3])) && p('id,status') && e('8,active');  // 测试激活分支 8
r($branch->activateTest($branchID[4])) && p('id,status') && e('10,active'); // 测试激活分支 10
r($branch->activateTest($branchID[5])) && p('id,status') && e('1,active');  // 测试激活分支 1
