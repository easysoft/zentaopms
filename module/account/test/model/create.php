#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('account')->gen(0);

/**

title=accountModel->create();
timeout=0
cid=1

*/

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
$accountModel = $tester->loadModel('account');
$accountID    = $accountModel->create($account);
$account      = $accountModel->getByID($accountID);
r($account) && p('name,provider,account,password') && e('测试账号,aliyun,admin,admin'); // 检查插入数据库的数据。
