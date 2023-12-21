#!/usr/bin/env php
<?php
/**
title=测试 userModel->getListByAccounts();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$user = zdTable('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix('user');
$user->realname->range('1-5')->prefix('用户');
$user->role->range('po,pd,qa,qd,pm');
$user->type->range('inside{3},outside{2}');
$user->deleted->range('0-1');
$user->gen(5);

$userTest = new userTest();
$accounts = array('user1', 'user2', 'user3', 'user4', 'user5');

$users = $userTest->getListByAccountsTest(array());
r(count($users)) && p() && e(0); // 传入参数为空数组，返回空数组。

$users = $userTest->getListByAccountsTest(array('admin'));
r(count($users)) && p() && e(0); // 传入参数为非空数组，其中包含的账号在数据库中不存在，返回空数组。

/* 使用默认的 $keyField 参数查询。*/
$users = $userTest->getListByAccountsTest($accounts);
r(count($users)) && p()                  && e(5);            // 传入参数为非空数组，包含 5 个账号，返回数组包含 5 个用户。
r($users)        && p('1001:id,account') && e('1001,user1'); // 传入参数为非空数组，包含 5 个账号，返回数组键为 1001 的用户 id 是 1001，账号是 user1。
r($users)        && p('1005:id,account') && e('1005,user5'); // 传入参数为非空数组，包含 5 个账号，返回数组键为 1005 的用户 id 是 1005，账号是 user5。
r($users)        && p('user1')           && e('``');

/* $keyField 参数设为 id 后查询。*/
$users = $userTest->getListByAccountsTest($accounts, 'id');
r(count($users)) && p()                  && e(5);            // 传入参数为非空数组，包含 5 个账号，返回数组包含 5 个用户。
r($users)        && p('1001:id,account') && e('1001,user1'); // 传入参数为非空数组，包含 5 个账号，返回数组键为 1001 的用户 id 是 1001，账号是 user1。
r($users)        && p('1005:id,account') && e('1005,user5'); // 传入参数为非空数组，包含 5 个账号，返回数组键为 1005 的用户 id 是 1005，账号是 user5。
r($users)        && p('user1')           && e('``');

/* $keyField 参数设为 account 后查询。*/
$users = $userTest->getListByAccountsTest($accounts, 'account');
r(count($users)) && p()                   && e(5);            // 传入参数为非空数组，包含 5 个账号，返回数组包含 5 个用户。
r($users)        && p('user1:id,account') && e('1001,user1'); // 传入参数为非空数组，包含 5 个账号，返回数组键为 1001 的用户 id 是 1001，账号是 user1。
r($users)        && p('user5:id,account') && e('1005,user5'); // 传入参数为非空数组，包含 5 个账号，返回数组键为 1005 的用户 id 是 1005，账号是 user5。
r($users)        && p('1001')             && e('``');
