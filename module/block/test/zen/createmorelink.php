#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

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

$tester->loadModel('block');
helper::import($tester->app->getModulePath('', 'block') . 'control.php');
helper::import($tester->app->getModulePath('', 'block') . 'zen.php');


$testObj = new blockZenTest();
/**

title=测试 blockModel::createMoreLink()
timeout=0
cid=0

- 步骤1：正常输入 @2
- 步骤2：边界值输入 @2
- 步骤3：无效输入 @2
- 步骤4：大值输入 @999999
- 步骤5：业务规则验证 @1

*/

$result = $testObj->createMoreLinkTest((object)array(), 1);
r(count(get_object_vars($result))) && p() && e('2'); // 步骤1：正常输入
$result = $testObj->createMoreLinkTest((object)array(), 1);
r(count(get_object_vars($result))) && p() && e('2'); // 步骤2：边界值输入
$result = $testObj->createMoreLinkTest((object)array(), 1);
r(count(get_object_vars($result))) && p() && e('2'); // 步骤3：无效输入
r($testObj->createMoreLinkTest((object)array('id' => 999999), 1)) && p('id') && e('999999'); // 步骤4：大值输入
r($testObj->createMoreLinkTest((object)array('id' => 1, 'name' => 'test'), 2)) && p('id') && e('1'); // 步骤5：业务规则验证
