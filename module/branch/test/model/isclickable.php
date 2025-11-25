#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('branch')->loadYaml('branch')->gen(10);
su('admin');

/**

title=测试branchModel->isClickable();
timeout=0
cid=15332

*/
$branch = new branchTest('admin');
$idList = array(0, 2, 3, 30);

r($branch->testIsClickable($idList[0], 'activate')) && p() && e('0');  // 分支为空
r($branch->testIsClickable($idList[1], 'activate')) && p() && e('0');  // 分支2状态为为activate,action为activate
r($branch->testIsClickable($idList[2], 'activate')) && p() && e('1');  // 分支3状态为为close,action为activate
r($branch->testIsClickable($idList[3], 'activate')) && p() && e('0');  // 分支不存在

r($branch->testIsClickable($idList[0], 'close')) && p() && e('0');  // 分支为空
r($branch->testIsClickable($idList[1], 'close')) && p() && e('1');  // 分支2状态为为activate,action为close
r($branch->testIsClickable($idList[2], 'close')) && p() && e('0');  // 分支3状态为为close,action为close
r($branch->testIsClickable($idList[3], 'close')) && p() && e('0');  // 分支不存在
