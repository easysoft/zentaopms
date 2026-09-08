#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

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

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

su('admin');
$zd_entry = zenData('entry');
$zd_entry->id->range('1');
$zd_entry->name->range('GitFox');
$zd_entry->account->range('admin');
$zd_entry->code->range('gitfox');
$zd_entry->key->range('gitfox');
$zd_entry->freePasswd->range('1');
$zd_entry->ip->range('*');
$zd_entry->createdBy->range('admin');
$zd_entry->createdDate->range('`2026-01-01 00:00:00`');
$zd_entry->calledTime->range('0');
$zd_entry->editedBy->range('admin');
$zd_entry->editedDate->range('`2026-01-01 00:00:00`');
$zd_entry->deleted->range('0');
$zd_entry->gen(1);



$tester->loadModel('artifact');


$testObj = new artifactModelTest();
/**

title=测试 artifactModel::getAssetListByNodeID()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @1

*/

$result = $testObj->getAssetListByNodeIDTest('1', 0, 'editedDate_desc', null);
r(str_contains($result, '实体 ID 不合法')) && p() && e('1'); // 步骤1：正常输入
$result = $testObj->getAssetListByNodeIDTest('', 0, 'editedDate_desc', null);
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->getAssetListByNodeIDTest('abc', 0, 'editedDate_desc', null);
r(str_contains($result, '实体 ID 不合法')) && p() && e('1'); // 步骤3：无效输入
$result = $testObj->getAssetListByNodeIDTest('999999', 0, 'editedDate_desc', null);
r(str_contains($result, '实体 ID 不合法')) && p() && e('1'); // 步骤4：大值输入
$result = $testObj->getAssetListByNodeIDTest('test', 2, 'editedDate_desc', null);
r(str_contains($result, '实体 ID 不合法')) && p() && e('1'); // 步骤5：业务规则验证
