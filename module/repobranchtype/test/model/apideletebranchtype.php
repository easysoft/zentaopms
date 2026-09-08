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



$tester->loadModel('repobranchtype');


$testObj = new repobranchtypeModelTest();
/**

title=测试 repobranchtypeModel::apiDeleteBranchType()
timeout=0
cid=0

- 步骤1：正常输入 @1
- 步骤2：边界值输入 @daoError:{"apiMessage":"分支类型未找到。"}
- 步骤3：无效输入 @daoError:{"apiMessage":"分支类型未找到。"}
- 步骤4：大值输入 @daoError:{"apiMessage":"分支类型未找到。"}
- 步骤5：业务规则验证 @daoError:{"apiMessage":"分支类型未找到。"}

*/

$runID = date('YmdHis') . '-' . getmypid();
$branchTypeKey = 'unittestdel' . date('YmdHis') . getmypid();
$branchType = (object)array(
    'name'     => '单测删除分支类型' . $runID,
    'key'      => $branchTypeKey,
    'prefixes' => array($branchTypeKey . '/'),
    'desc'     => 'unit test delete branch type'
);
$createdType = $tester->repobranchtype->apiCreateBranchType(0, $branchType);
$createdTypeID = is_object($createdType) && isset($createdType->id) ? (int)$createdType->id : 999999999;
$missingTypeID = 999999999;

r($testObj->apiDeleteBranchTypeTest((object)array('id' => 0), $createdTypeID)) && p() && e('1'); // 步骤1：正常输入
r($testObj->apiDeleteBranchTypeTest((object)array('id' => 0), $missingTypeID)) && p() && e('daoError:{"apiMessage":"分支类型未找到。"}'); // 步骤2：边界值输入
r($testObj->apiDeleteBranchTypeTest((object)array(), $missingTypeID)) && p() && e('daoError:{"apiMessage":"分支类型未找到。"}'); // 步骤3：无效输入
r($testObj->apiDeleteBranchTypeTest((object)array('id' => 999999), $missingTypeID)) && p() && e('daoError:{"apiMessage":"分支类型未找到。"}'); // 步骤4：大值输入
r($testObj->apiDeleteBranchTypeTest((object)array('id' => 1, 'name' => 'test'), $missingTypeID)) && p() && e('daoError:{"apiMessage":"分支类型未找到。"}'); // 步骤5：业务规则验证
