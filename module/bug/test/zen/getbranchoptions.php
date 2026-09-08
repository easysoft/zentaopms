#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

error_reporting(E_ERROR);

zenData('branch')->gen(0);
$productTable = zenData('product');
$productTable->id->range('1-2');
$productTable->name->range('正常产品1,正常产品2');
$productTable->type->range('normal{2}');
$productTable->status->range('normal{2}');
$productTable->deleted->range('0{2}');
$productTable->gen(2);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('bug');
helper::import($tester->app->getModulePath('', 'bug') . 'control.php');
helper::import($tester->app->getModulePath('', 'bug') . 'zen.php');


$testObj = new bugZenTest();
/**

title=测试 bugModel::getBranchOptions()
timeout=0
cid=0

- 步骤1：正常输入 @主干
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @主干

*/

$result = $testObj->getBranchOptionsTest(1);
r(implode(',', $result)) && p() && e('主干'); // 步骤1：正常输入
$result = $testObj->getBranchOptionsTest(0);
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->getBranchOptionsTest(-1);
r(count($result)) && p() && e('0'); // 步骤3：无效输入
$result = $testObj->getBranchOptionsTest(999999);
r(count($result)) && p() && e('0'); // 步骤4：大值输入
$result = $testObj->getBranchOptionsTest(2);
r(implode(',', $result)) && p() && e('主干'); // 步骤5：业务规则验证
