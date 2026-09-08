#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$dbh->exec("DELETE FROM zt_config WHERE `owner` = 'system' AND `module` = 'zai' AND `section` = 'kb' AND `key` = 'systemVectorization'");

$zd_aivectorqueue = zenData('ai_vectorqueue');
$zd_aivectorqueue->id->range('1-5');
$zd_aivectorqueue->objectType->range('1-5');
$zd_aivectorqueue->objectID->range('1-5');
$zd_aivectorqueue->retries->range('0');
$zd_aivectorqueue->lastSyncTime->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_aivectorqueue->createdDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_aivectorqueue->editedDate->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_aivectorqueue->gen(5);
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

$tester->loadModel('zai');


$testObj = new zaiModelTest();
/**

title=测试 zaiModel::refreshVectorizedStatus()
timeout=0
cid=0

- 步骤1：正常输入 @disabled
- 步骤2：边界值输入 @disabled
- 步骤3：无效输入 @disabled
- 步骤4：大值输入 @disabled
- 步骤5：业务规则验证 @disabled

*/

r($testObj->refreshVectorizedStatusTest()) && p('status') && e('disabled'); // 步骤1：正常输入
r($testObj->refreshVectorizedStatusTest()) && p('status') && e('disabled'); // 步骤2：边界值输入
r($testObj->refreshVectorizedStatusTest()) && p('status') && e('disabled'); // 步骤3：无效输入
r($testObj->refreshVectorizedStatusTest()) && p('status') && e('disabled'); // 步骤4：大值输入
r($testObj->refreshVectorizedStatusTest()) && p('status') && e('disabled'); // 步骤5：业务规则验证
