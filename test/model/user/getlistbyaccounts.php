#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix("account");
$user->realname->range('1-5')->prefix("用户名");
$user->role->range('po,pd,qa,qd,pm');
$user->type->range('inside{3},outside{2}');
$user->deleted->range('0-1');
$user->gen(5);

/**

title=测试 userModel->getListByAccounts();
cid=1
pid=1

按照给定的用户名列表查询用户信息，获取用户ID为1001的用户真实姓名和角色 >> 用户名1,po
按照给定的用户名列表查询用户信息，获取用户ID为1002的用户真实姓名和角色 >> 用户名2,pd
按照给定的用户名列表查询用户信息，获取用户ID为1003的用户真实姓名和角色 >> 用户名3,qa
按照给定的用户名列表查询用户信息，获取用户ID为1004的用户真实姓名和角色 >> 用户名4,qd
按照给定的用户名列表查询用户信息，获取查询出的用户列表数量 >> 5
删除account4后，按照给定的用户名列表查询用户信息，获取用户account为account2的用户真实姓名和角色 >> 用户名2,pd
删除account4后，按照给定的用户名列表查询用户信息，获取查询出的用户列表数量 >> 4

*/
$accounts = array('account1', 'account2', 'account3', 'account4', 'account5');
$keyFiled = array('id', 'account');
$count    = array(0 => false, 1 => true);

$user = new userTest();
r($user->getListByAccountsTest($accounts, $keyFiled[0], $count[0])) && p('1001:realname,role')     && e('用户名1,po');                 // 按照给定的用户名列表查询用户信息，获取用户ID为1001的用户真实姓名和角色
r($user->getListByAccountsTest($accounts, $keyFiled[0], $count[0])) && p('1002:realname,role')     && e('用户名2,pd');                 // 按照给定的用户名列表查询用户信息，获取用户ID为1002的用户真实姓名和角色
r($user->getListByAccountsTest($accounts, $keyFiled[0], $count[0])) && p('1003:realname,role')     && e('用户名3,qa');                 // 按照给定的用户名列表查询用户信息，获取用户ID为1003的用户真实姓名和角色
r($user->getListByAccountsTest($accounts, $keyFiled[0], $count[0])) && p('1004:realname,role')     && e('用户名4,qd');                 // 按照给定的用户名列表查询用户信息，获取用户ID为1004的用户真实姓名和角色
r($user->getListByAccountsTest($accounts, $keyFiled[0], $count[1])) && p()                         && e('5');                          // 按照给定的用户名列表查询用户信息，获取查询出的用户列表数量
unset($accounts[3]);
r($user->getListByAccountsTest($accounts, $keyFiled[1], $count[0])) && p('account2:realname,role') && e('用户名2,pd');                 // 删除account4后，按照给定的用户名列表查询用户信息，获取用户account为account2的用户真实姓名和角色
r($user->getListByAccountsTest($accounts, $keyFiled[1], $count[1])) && p()                         && e('4');                          // 删除account4后，按照给定的用户名列表查询用户信息，获取查询出的用户列表数量
