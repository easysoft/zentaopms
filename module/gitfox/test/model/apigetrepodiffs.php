#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('gitfox');


$testObj = new gitfoxModelTest();
/**

title=测试 gitfoxModel::apiGetRepoDiffs()
timeout=0
cid=0

- 步骤1：正常输入 @90
- 步骤2：边界值输入 @90
- 步骤3：无效输入 @90
- 步骤4：大值输入 @90
- 步骤5：业务规则验证 @90

*/

$result = $testObj->apiGetRepoDiffsTest(1, '1', '1');
r(strlen($result)) && p() && e('90'); // 步骤1：正常输入
$result = $testObj->apiGetRepoDiffsTest(0, '1', '1');
r(strlen($result)) && p() && e('90'); // 步骤2：边界值输入
$result = $testObj->apiGetRepoDiffsTest(-1, '1', '1');
r(strlen($result)) && p() && e('90'); // 步骤3：无效输入
$result = $testObj->apiGetRepoDiffsTest(999999, '1', '1');
r(strlen($result)) && p() && e('90'); // 步骤4：大值输入
$result = $testObj->apiGetRepoDiffsTest(2, 'test', '1');
r(strlen($result)) && p() && e('90'); // 步骤5：业务规则验证
