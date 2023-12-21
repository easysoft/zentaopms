#!/usr/bin/env php
<?php
/**
title=测试 userModel->identifyUser();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('user');
$table->password->range(md5(123456));
$table->avatar->range('/www/data/upload/1/202311/29144321062056c4');
$table->gen(2);

su('admin');

$userTest = new userTest();

$random = updateSessionRandom();
$admin  = $userTest->getByIdTest('admin');
$user1  = $userTest->getByIdTest('user1');

r($userTest->identifyUserTest('user2', '123456'))     && p() && e(0); // 用户名不存在，密码不为空，返回 false。
r($userTest->identifyUserTest('admin', md5(123456)))  && p() && e(0); // 用户名存在，密码使用 md5 加密，密码不正确，返回 false。
r($userTest->identifyUserTest('admin', sha1(123456))) && p() && e(0); // 用户名存在，密码使用 sha1 加密，密码不正确，返回 false。
r($userTest->identifyUserTest('admin', '654321'))     && p() && e(0); // 用户名存在，密码使用明文，密码不正确，返回 false。

/**
 * 检测验证超级管理员用户身份。
 */
$md5Password  = md5($admin->password . $random);
$sha1Password = sha1($admin->account . $admin->password . strtotime($admin->last));
r($userTest->identifyUserTest($admin->account, '123456'))      && p('account') && e('admin'); // 用户名存在，密码使用明文，密码正确，返回用户。
r($userTest->identifyUserTest($admin->account, $md5Password))  && p('account') && e('admin'); // 用户名存在，密码使用 md5 加密，密码正确，返回用户。
r($userTest->identifyUserTest($admin->account, $sha1Password)) && p('account') && e('admin'); // 用户名存在，密码使用 sha1 加密，密码正确，返回用户。

/**
 * 检测验证普通用户身份。
 */
$md5Password  = md5($user1->password . $random);
$sha1Password = sha1($user1->account . $user1->password . strtotime($user1->last));
r($userTest->identifyUserTest($user1->account, '123456'))      && p('account') && e('user1'); // 用户名存在，密码使用明文，密码正确，返回用户。
r($userTest->identifyUserTest($user1->account, $md5Password))  && p('account') && e('user1'); // 用户名存在，密码使用 md5 加密，密码正确，返回用户。
r($userTest->identifyUserTest($user1->account, $sha1Password)) && p('account') && e('user1'); // 用户名存在，密码使用 sha1 加密，密码正确，返回用户。
