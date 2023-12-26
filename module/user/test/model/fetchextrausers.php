#!/usr/bin/env php
<?php
/**
title=测试 userModel->fetchExtraUsers();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(2);

$userTest = new userTest();

r($userTest->fetchExtraUsersTest('',      '*', '')) && p() && e(0); // usersToAppended 参数为空字符串，返回空数组。
r($userTest->fetchExtraUsersTest(array(), '*', '')) && p() && e(0); // usersToAppended 参数为空数组，返回空数组。

$users = $userTest->fetchExtraUsersTest('admin, user1', '*', '');
r(count($users)) && p()            && e(2);       // usersToAppended 参数为字符串，包含 2 个用户，返回 2 个用户。
r($users)        && p('0:account') && e('admin'); // 返回的第 1 个用户的 account 字段值为 admin。
r($users)        && p('1:account') && e('user1'); // 返回的第 2 个用户的 account 字段值为 user1。

$users = $userTest->fetchExtraUsersTest(array('admin', 'user1'), '*', '');
r(count($users)) && p()            && e(2);       // usersToAppended 参数为数组，包含 2 个用户，返回 2 个用户。
r($users)        && p('0:account') && e('admin'); // 返回的第 1 个用户的 account 字段值为 admin。
r($users)        && p('1:account') && e('user1'); // 返回的第 2 个用户的 account 字段值为 user1。

$users = $userTest->fetchExtraUsersTest(array('admin', 'user1'), 'id, account', '');
r(count($users)) && p()             && e(2);       // usersToAppended 参数为数组，包含 2 个用户，指定查找 id 和 account 字段，返回 2 个用户。
r($users)        && p('0:id')       && e(1);       // 返回的第 1 个用户的 id 字段值为 1。
r($users)        && p('0:account')  && e('admin'); // 返回的第 1 个用户的 account 字段值为 admin。
r($users)        && p('0:realname') && e('~~');    // 返回的第 1 个用户没有 realname 字段。
r($users)        && p('1:id')       && e(2);       // 返回的第 2 个用户的 id 字段值为 2。
r($users)        && p('1:account')  && e('user1'); // 返回的第 2 个用户的 account 字段值为 user1。
r($users)        && p('0:realname') && e('~~');    // 返回的第 2 个用户没有 realname 字段。

$users = $userTest->fetchExtraUsersTest(array('admin', 'user1'), 'id, account', 'id');
r(count($users))      && p()    && e(2); // usersToAppended 参数为数组，包含 2 个用户，指定查找 id 和 account 字段，指定以 id 字段为键，返回 2 个用户。
r(array_keys($users)) && p('0') && e(1); // 返回的第 1 个用户的键为 1。
r(array_keys($users)) && p('1') && e(2); // 返回的第 2 个用户的键为 2。

$users = $userTest->fetchExtraUsersTest(array('admin', 'user1', 'user2'), 'id, account', 'id');
r(count($users)) && p() && e(2); // usersToAppended 参数为数组，包含 3 个用户，指定查找 id 和 account 字段，指定以 id 字段为键，返回 2 个用户。
