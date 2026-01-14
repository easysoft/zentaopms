#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->activate();
timeout=0
cid=15318

*/

$branchID = array(2, 4, 6, 8, 10, 1);

$branch = new branchModelTest();

r($branch->activateTest($branchID[0])) && p('id,status') && e('2,active');  // 测试激活分支 2
r($branch->activateTest($branchID[1])) && p('id,status') && e('4,active');  // 测试激活分支 4
r($branch->activateTest($branchID[2])) && p('id,status') && e('6,active');  // 测试激活分支 6
r($branch->activateTest($branchID[3])) && p('id,status') && e('8,active');  // 测试激活分支 8
r($branch->activateTest($branchID[4])) && p('id,status') && e('10,active'); // 测试激活分支 10
r($branch->activateTest($branchID[5])) && p('id,status') && e('1,active');  // 测试激活分支 1
