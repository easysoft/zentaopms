#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix("account");
$user->realname->range('1-5')->prefix("用户名");
$user->deleted->range('0-1');
$user->gen(5);

/**

title=测试 userModel::setUserList();
cid=1
pid=1

测试获取account1的是否在列表中 >> 1
测试获取account2的是否在列表中 >> 1
测试获取account3的是否在列表中 >> 1
测试获取account4的是否在列表中 >> 1
测试获取account5的是否在列表中 >> 0

*/
$users = array('account1' => '用户名1', 'account2' => '用户名2', 'account3' => '用户名3', 'account4' => '用户名4');

$user = new userTest();
r($user->setUserListTest($users, 'account1')) && p() && e('1');    // 测试获取account1的是否在列表中
r($user->setUserListTest($users, 'account2')) && p() && e('1');    // 测试获取account2的是否在列表中
r($user->setUserListTest($users, 'account3')) && p() && e('1');    // 测试获取account3的是否在列表中
r($user->setUserListTest($users, 'account4')) && p() && e('1');    // 测试获取account4的是否在列表中
r($user->setUserListTest($users, 'account5')) && p() && e('0');    // 测试获取account5的是否在列表中
