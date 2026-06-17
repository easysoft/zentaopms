#!/usr/bin/env php
<?php

/**

title=测试 mailModel->processToAndCC();
cid=17036

- toList为空 @0
- toList只有me账号 @0
- toList有me账号，和另一个账号属性1 @user1
- toList只有有me账号，ccList有多个账号属性1 @user2
- ccList为空 @0
- ccList只有me账号 @0
- ccList有me账号，和另一个账号属性1 @user2
- ccList有me账号，和另两个一个账号，而toList只有有me账号属性1 @user3
- toList去除删除用户和blockUser @admin
- ccList去除删除用户和blockUser @user2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->dao->delete()->from(TABLE_USER)->where('id')->gt(0)->exec();

$users = array(
    array('account' => 'admin', 'realname' => 'admin', 'email' => '10001000@qq.com', 'gender' => 'f'),
    array('account' => 'user1', 'realname' => '用户1', 'email' => '10021002@163.com', 'gender' => 'm'),
    array('account' => 'user2', 'realname' => '用户2', 'email' => '10041004@gmail.com', 'gender' => 'f'),
    array('account' => 'user3', 'realname' => '用户3', 'email' => '10061006@qq.com', 'gender' => 'm'),
    array('account' => 'user4', 'realname' => '用户4', 'email' => '10081008@163.com', 'gender' => 'f'),
);

foreach($users as $userData)
{
    $user = new stdClass();
    $user->company  = 1;
    $user->type     = 'inside';
    $user->dept     = 1;
    $user->account  = $userData['account'];
    $user->password = md5('123456');
    $user->role     = 'qa';
    $user->realname = $userData['realname'];
    $user->gender   = $userData['gender'];
    $user->email    = $userData['email'];
    $user->visions  = 'rnd,lite,or';
    $user->deleted  = '0';
    $tester->dao->insert(TABLE_USER)->data($user)->exec();
}

su('admin');

$mailModel = $tester->loadModel('mail');
$mailModel->app->user->account = 'admin';

list($toList1, $ccList1) = $mailModel->processToAndCC('', '');
list($toList2, $ccList2) = $mailModel->processToAndCC('admin', 'admin');
list($toList3, $ccList3) = $mailModel->processToAndCC('admin,user1', 'admin,user2');
list($toList4, $ccList4) = $mailModel->processToAndCC('admin', 'admin,user2,user3');

r($toList1) && p()    && e('0');           //toList为空
r($toList2) && p()    && e('0');           //toList只有me账号
r($toList3) && p('1') && e('user1');       //toList有me账号，和另一个账号
r($toList4) && p('1') && e('user2');       //toList只有有me账号，ccList有多个账号
r($ccList1) && p()    && e('0');           //ccList为空
r($ccList2) && p()    && e('0');           //ccList只有me账号
r($ccList3) && p('1') && e('user2');       //ccList有me账号，和另一个账号
r($ccList4) && p('1') && e('user3');       //ccList有me账号，和另两个一个账号，而toList只有有me账号

$mailModel->config->message->blockUser = 'user1';
list($toList5, $ccList5) = $mailModel->processToAndCC('admin,user1,dev1', 'user2,dev1', true);

r($toList5) && p()    && e('admin');  //toList去除删除用户和blockUser
r($ccList5) && p()    && e('user2');  //ccList去除删除用户和blockUser
