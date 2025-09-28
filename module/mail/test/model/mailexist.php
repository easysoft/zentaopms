#!/usr/bin/env php
<?php

/**

title=测试 mailModel::mailExist();
cid=0

- 测试有邮箱用户存在的情况 >> 返回用户对象
- 测试返回用户的账号字段 >> 返回用户账号
- 测试返回用户的邮箱字段 >> 返回用户邮箱
- 测试所有用户邮箱为空的情况 >> 返回false
- 测试没有用户记录的情况 >> 返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mail.unittest.class.php';

$user = zenData('user');
$user->account->range('admin');
$user->email->range('admin@test.com');
$user->realname->range('管理员');
$user->password->range('123456');
$user->role->range('admin');
$user->deleted->range('0');
$user->gen(1);

su('admin');

$mailTest = new mailTest();

r($mailTest->mailExistTest()) && p() && e('~~');
r($mailTest->mailExistTest()) && p('account') && e('admin');
r($mailTest->mailExistTest()) && p('email') && e('admin@test.com');

$user = zenData('user');
$user->account->range('user1');
$user->email->range('');
$user->realname->range('用户1');
$user->password->range('123456');
$user->role->range('dev');
$user->deleted->range('0');
$user->gen(1);

r($mailTest->mailExistTest()) && p() && e('0');

$user = zenData('user');
$user->gen(0);

r($mailTest->mailExistTest()) && p() && e('0');