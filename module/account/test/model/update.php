#!/usr/bin/env php
<?php

/**

title=accountModel->update();
timeout=0
cid=0

- 检查插入数据库的数据。
 - 属性id @1
 - 属性name @运维账号1
 - 属性provider @qingyun
- 检查插入数据库的数据。
 - 属性id @1
 - 属性name @测试账号
 - 属性provider @aliyun
 - 属性account @admin
 - 属性password @admin
- 必填项检查。第name条的0属性 @『名称』不能为空。
- 邮箱格式检查。第email条的0属性 @『邮件』应当为合法的EMAIL。
- 手机格式检查。第mobile条的0属性 @『手机』应当为合法的手机号码。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('account')->gen(5);

$account = new stdclass();
$account->name        = '测试账号';
$account->provider    = 'aliyun';
$account->adminURI    = '';
$account->account     = 'admin';
$account->password    = 'admin';
$account->email       = 'admin@163.com';
$account->mobile      = '';
$account->type        = '';
$account->status      = '';
$account->extra       = '';
$account->createdBy   = 'admin';
$account->createdDate = date('Y-m-d H:i:s');

global $tester;
$accountID    = 1;
$accountModel = $tester->loadModel('account');
$oldAccount   = $accountModel->dao->select('*')->from(TABLE_ACCOUNT)->where('id')->eq($accountID)->fetch();
$accountModel->update($accountID, $account);
$newAccount   = $accountModel->dao->select('*')->from(TABLE_ACCOUNT)->where('id')->eq($accountID)->fetch();

r($oldAccount) && p('id,name,provider') && e('1,运维账号1,qingyun'); // 检查插入数据库的数据。
r($newAccount) && p('id,name,provider,account,password') && e('1,测试账号,aliyun,admin,admin'); // 检查插入数据库的数据。

$account->name  = '';
$accountModel->create($account);
r(dao::getError()) && p('name:0') && e('『名称』不能为空。'); //必填项检查。

$account->name  = '测试账号';
$account->email = 'test';
$accountModel->create($account);
r(dao::getError()) && p('email:0') && e('『邮件』应当为合法的EMAIL。'); // 邮箱格式检查。

$account->email  = 'admin@163.com';
$account->mobile = 'test';
$accountModel->create($account);
r(dao::getError()) && p('mobile:0') && e('『手机』应当为合法的手机号码。'); // 手机格式检查。