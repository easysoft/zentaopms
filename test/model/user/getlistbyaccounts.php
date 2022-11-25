#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getListByAccounts();
cid=1
pid=1

按照给定的用户名列表查询用户信息，获取用户ID为1的用户真实姓名和角色 >> admin,qa
按照给定的用户名列表查询用户信息，获取查询出的用户列表数量 >> 3
按照给定的用户名列表查询用户信息，获取用户ID为0的用户信息 >> Error: Cannot get index 0.

*/
$accounts = array('admin', 'program1whitelist', 'user10', 'ccsdqq@!');
$count    = array(0 => false, 1 => true);

$user = new userTest();
r($user->getListByAccountsTest($accounts, $count[0])) && p('1:realname,role')   && e('admin,qa');                   //按照给定的用户名列表查询用户信息，获取用户ID为1的用户真实姓名和角色
r($user->getListByAccountsTest($accounts, $count[1])) && p()                    && e('3');                          //按照给定的用户名列表查询用户信息，获取查询出的用户列表数量
r($user->getListByAccountsTest($accounts, $count[0])) && p('0:realname')        && e('Error: Cannot get index 0.'); //按照给定的用户名列表查询用户信息，获取用户ID为0的用户信息