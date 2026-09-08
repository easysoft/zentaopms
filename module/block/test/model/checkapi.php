#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_config = zenData('config');
$zd_config->id->range('1-5');
$zd_config->vision->range('1-5');
$zd_config->owner->range('1-5');
$zd_config->module->range('1-5');
$zd_config->section->range('1-5');
$zd_config->key->range('1-5');
$zd_config->gen(5);
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


$testObj = new blockModelTest();
/**

title=测试 blockModel::checkAPI()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

r($testObj->checkAPITest('1')) && p() && e('0'); // 步骤1：正常输入
r($testObj->checkAPITest('')) && p() && e('0'); // 步骤2：边界值输入
r($testObj->checkAPITest('abc')) && p() && e('0'); // 步骤3：无效输入
r($testObj->checkAPITest('999999')) && p() && e('0'); // 步骤4：大值输入
r($testObj->checkAPITest('test')) && p() && e('0'); // 步骤5：业务规则验证
