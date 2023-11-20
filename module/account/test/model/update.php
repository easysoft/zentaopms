#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('account')->gen(5);

/**

title=accountModel->update();
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
$accountID    = 1;
$accountModel = $tester->loadModel('account');
$oldAccount   = $accountModel->getByID($accountID);
$accountModel->update($accountID, $account);
$newAccount   = $accountModel->getByID($accountID);

r($oldAccount) && p('id,name,provider') && e('1,运维账号1,qingyun'); // 检查插入数据库的数据。
r($newAccount) && p('id,name,provider,account,password') && e('1,测试账号,aliyun,admin,admin'); // 检查插入数据库的数据。
