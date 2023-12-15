#!/usr/bin/env php
<?php

/**

title=accountModel->create();
cid=0

- 检查插入数据库的数据。
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

zdTable('account')->gen(0);

$accountData = new stdclass();
$accountData->name        = '测试账号';
$accountData->provider    = 'aliyun';
$accountData->adminURI    = '';
$accountData->account     = 'admin';
$accountData->password    = 'admin';
$accountData->email       = 'admin@163.com';
$accountData->mobile      = '';
$accountData->type        = '';
$accountData->status      = '';
$accountData->extra       = '';
$accountData->createdBy   = 'admin';
$accountData->createdDate = date('Y-m-d H:i:s');

global $tester;
$accountModel = $tester->loadModel('account');
$accountID    = $accountModel->create($accountData);
$account      = $accountModel->dao->select('*')->from(TABLE_ACCOUNT)->where('id')->eq($accountID)->fetch();
r($account) && p('name,provider,account,password') && e('测试账号,aliyun,admin,admin'); // 检查插入数据库的数据。

$accountData->name  = '';
$accountModel->create($accountData);
r(dao::getError()) && p('name:0') && e('『名称』不能为空。'); //必填项检查。

$accountData->name  = '测试账号';
$accountData->email = 'test';
$accountModel->create($accountData);
r(dao::getError()) && p('email:0') && e('『邮件』应当为合法的EMAIL。'); // 邮箱格式检查。

$accountData->email  = 'admin@163.com';
$accountData->mobile = 'test';
$accountModel->create($accountData);
r(dao::getError()) && p('mobile:0') && e('『手机』应当为合法的手机号码。'); // 手机格式检查。
