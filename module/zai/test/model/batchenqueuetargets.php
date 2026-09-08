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

$tester->loadModel('zai');


$testObj = new zaiModelTest();
/**

title=测试 zaiModel::batchEnqueueTargets()
timeout=0
cid=0

- 步骤1：正常输入 @fail,1,未启用
- 步骤2：边界值输入 @fail,1,未启用
- 步骤3：无效输入 @fail,1,未启用
- 步骤4：大值输入 @fail,1,未启用
- 步骤5：业务规则验证 @fail,1,未启用

*/

$result = $testObj->batchEnqueueTargetsTest('http://127.0.0.1:1/', 0, 0);
r(implode(',', $result)) && p() && e('fail,1,未启用'); // 步骤1：正常输入
$result = $testObj->batchEnqueueTargetsTest('http://127.0.0.1:1/', 0, 0);
r(implode(',', $result)) && p() && e('fail,1,未启用'); // 步骤2：边界值输入
$result = $testObj->batchEnqueueTargetsTest('http://127.0.0.1:1/', 0, 0);
r(implode(',', $result)) && p() && e('fail,1,未启用'); // 步骤3：无效输入
$result = $testObj->batchEnqueueTargetsTest('http://127.0.0.1:1/', 0, 0);
r(implode(',', $result)) && p() && e('fail,1,未启用'); // 步骤4：大值输入
$result = $testObj->batchEnqueueTargetsTest('http://127.0.0.1:1/', 2, 0);
r(implode(',', $result)) && p() && e('fail,1,未启用'); // 步骤5：业务规则验证
