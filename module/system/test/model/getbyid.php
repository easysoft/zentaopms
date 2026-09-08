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

$tester->loadModel('system');

$zd_system = zenData('system');
$zd_system->id->range('1-2');
$zd_system->name->range('系统1,系统2');
$zd_system->product->range('0');
$zd_system->integrated->range('0');
$zd_system->latestRelease->range('0');
$zd_system->status->range('active');
$zd_system->deleted->range('0');
$zd_system->gen(2);


$testObj = new systemModelTest();
/**

title=测试 systemModel::getByID()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @error:TypeError
- 步骤3：无效输入 @error:TypeError
- 步骤4：大值输入 @error:TypeError
- 步骤5：业务规则验证 @2

*/

r($testObj->getByIDTest(1)) && p('id') && e('1'); // 步骤1：正常输入
r($testObj->getByIDTest(0)) && p() && e('error:TypeError'); // 步骤2：边界值输入
r($testObj->getByIDTest(-1)) && p() && e('error:TypeError'); // 步骤3：无效输入
r($testObj->getByIDTest(999999)) && p() && e('error:TypeError'); // 步骤4：大值输入
r($testObj->getByIDTest(2)) && p('id') && e('2'); // 步骤5：业务规则验证
