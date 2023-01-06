#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix("account");
$user->realname->range('1-5')->prefix("用户名");
$user->email->range('account1@qq.com,account2@qq.com,account3@qq.com,account4@qq.com,account5@qq.com');
$user->deleted->range('0{5}');
$user->gen(5);

/**

title=测试 userModel->getPairs();
cid=1
pid=1

获取account1的邮箱和真实姓名 >> account1@qq.com,用户名1
获取account2的邮箱和真实姓名 >> account2@qq.com,用户名2
获取account3的邮箱和真实姓名 >> account3@qq.com,用户名3
获取account4的邮箱和真实姓名 >> account4@qq.com,用户名4
获取account5的邮箱和真实姓名 >> account5@qq.com,用户名5
从accounts中获取到的用户邮箱数量 >> 5

*/

$accounts = array('account1', 'account2', 'account3', 'account4', 'account5');
$user     = new userTest();
$emails   = $user->getRealNameAndEmailsTest($accounts);

r($emails['account1'])  && p('email,realname') && e('account1@qq.com,用户名1'); //获取account1的邮箱和真实姓名
r($emails['account2'])  && p('email,realname') && e('account2@qq.com,用户名2'); //获取account2的邮箱和真实姓名
r($emails['account3'])  && p('email,realname') && e('account3@qq.com,用户名3'); //获取account3的邮箱和真实姓名
r($emails['account4'])  && p('email,realname') && e('account4@qq.com,用户名4'); //获取account4的邮箱和真实姓名
r($emails['account5'])  && p('email,realname') && e('account5@qq.com,用户名5'); //获取account5的邮箱和真实姓名
r(count($emails))       && p()                 && e('5');                       //从accounts中获取到的用户邮箱数量
