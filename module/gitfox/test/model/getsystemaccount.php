#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getSystemAccount();
timeout=0
cid=0

- 步骤1：入口配置了账号时优先返回入口账号 @gitfoxadmin
- 步骤2：入口未配置账号时返回当前登录账号 @user1
- 步骤3：没有用户上下文时返回公司第一个管理员 @admin
- 步骤4：公司有多个管理员时返回第一个管理员 @user2
- 步骤5：没有公司数据时返回admin @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$gitfoxTest = new gitfoxModelTest();
$tester->dao->delete()->from(TABLE_COMPANY)->exec();

/* 步骤1：入口配置了账号时优先返回入口账号。*/
zenData('entry')->gen(0);
$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('gitfoxadmin');
$entry->code->range('gitfox');
$entry->key->range('gitfox');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('[2026-01-01 00:00:00]');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('[2026-01-01 00:00:00]');
$entry->deleted->range('0');
$entry->gen(1);
r($gitfoxTest->getSystemAccountTest()) && p() && e('gitfoxadmin'); // 步骤1：入口配置了账号时优先返回入口账号

/* 步骤2：入口未配置账号时返回当前登录账号。*/
$tester->dao->update(TABLE_ENTRY)->set('account')->eq('')->where('code')->eq('gitfox')->exec();
$tester->app->user->account = 'user1';
r($gitfoxTest->getSystemAccountTest()) && p() && e('user1'); // 步骤2：入口未配置账号时返回当前登录账号

/* 步骤3：没有用户上下文时返回公司第一个管理员。*/
$tester->app->user->account = 'guest';
$company = zenData('company');
$company->id->range('1');
$company->name->range('禅道软件');
$company->admins->range('admin');
$company->guest->range('0');
$company->gen(1);
r($gitfoxTest->getSystemAccountTest()) && p() && e('admin'); // 步骤3：没有用户上下文时返回公司第一个管理员

/* 步骤4：公司有多个管理员时返回第一个管理员。*/
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',user2,user3,')->where('id')->eq(1)->exec();
r($gitfoxTest->getSystemAccountTest()) && p() && e('user2'); // 步骤4：公司有多个管理员时返回第一个管理员

/* 步骤5：没有公司数据时返回admin。*/
$tester->dao->delete()->from(TABLE_COMPANY)->exec();
r($gitfoxTest->getSystemAccountTest()) && p() && e('admin'); // 步骤5：没有公司数据时返回admin

/* 恢复公司数据，避免影响后续用例的环境初始化。*/
$company = zenData('company');
$company->id->range('1');
$company->name->range('禅道软件');
$company->admins->range(',admin,');
$company->guest->range('0');
$company->gen(1);
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,')->where('id')->eq(1)->exec();
