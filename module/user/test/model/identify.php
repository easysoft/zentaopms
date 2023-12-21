#!/usr/bin/env php
<?php
/**
title=测试 userModel->identify();
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

global $app, $config;
$config->webRoot = '/';

$userTest = new userTest();

$random = updateSessionRandom();
$admin  = $userTest->getByIdTest('admin');
$user1  = $userTest->getByIdTest('user1');

r($userTest->identifyTest('admin', ''))           && p() && e(0); // 用户名不为空，密码为空，返回 false。
r($userTest->identifyTest('', '123456'))          && p() && e(0); // 用户名为空，密码不为空，返回 false。
r($userTest->identifyTest('a1_~!', '123456'))     && p() && e(0); // 用户名不合法，密码不为空，返回 false。
r($userTest->identifyTest('user2', '123456'))     && p() && e(0); // 用户名不存在，密码不为空，返回 false。
r($userTest->identifyTest('admin', md5(123456)))  && p() && e(0); // 用户名存在，密码使用 md5 加密，密码不正确，返回 false。
r($userTest->identifyTest('admin', sha1(123456))) && p() && e(0); // 用户名存在，密码使用 sha1 加密，密码不正确，返回 false。
r($userTest->identifyTest('admin', '654321'))     && p() && e(0); // 用户名存在，密码使用明文，密码不正确，返回 false。

/**
 * 检测验证超级管理员用户身份。
 */
r($userTest->identifyTest($admin->account, '123456'))                        && p('account') && e('admin'); // 用户名存在，密码使用明文，密码正确，返回用户。
r($userTest->identifyTest($admin->account, md5($admin->password . $random))) && p('account') && e('admin'); // 用户名存在，密码使用 md5 加密，密码正确，返回用户。

$admin = $userTest->getByIdTest($admin->account); // 重新获取用户信息，因为上面的操作会修改用户信息。
$user  = $userTest->identifyTest($admin->account, sha1($admin->account . $admin->password . strtotime($admin->last)));
r($user) && p('account') && e('admin');                                  // 用户名存在，密码使用 sha1 加密，密码正确，返回用户。
r($user) && p('admin')   && e(1);                                        // 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户是超级管理员。
r($user) && p('avatar')  && e('/data/upload/1/202311/29144321062056c4'); // 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户头像是相对路径。

$oldAdmin = $admin;
$admin    = $userTest->getByIdTest($admin->account);
r(strtotime($admin->last) == $tester->server->request_time) && p() && e(1); // admin 用户的最后登录时间是服务器的请求时间。
r($admin->ip              == $tester->server->remote_addr)  && p() && e(1); // admin 用户的 IP 是服务器的远程地址。
r($admin->visits          == $oldAdmin->visits + 1)         && p() && e(1); // admin 用户的访问次数是原来的访问次数加 1。

/* 检测安装过程中验证用户身份。*/
$app->installing = true;

$oldAdmin = $admin;
$admin    = $userTest->getByIdTest($admin->account);
r($admin->visits == $oldAdmin->visits) && p() && e(1); // admin 用户的访问次数不变。

/* 检测升级过程中验证用户身份。*/
$app->upgrading = true;

$oldAdmin = $admin;
$admin    = $userTest->getByIdTest($admin->account);
r($admin->visits == $oldAdmin->visits) && p() && e(1); // admin 用户的访问次数不变。

/**
 * 检测验证普通用户身份。
 */
$app->installing = false;
$app->upgrading  = false;
r($userTest->identifyTest($user1->account, '123456'))                        && p('account') && e('user1'); // 用户名存在，密码使用明文，密码正确，返回用户。
r($userTest->identifyTest($user1->account, md5($user1->password . $random))) && p('account') && e('user1'); // 用户名存在，密码使用 md5 加密，密码正确，返回用户。

$user1 = $userTest->getByIdTest($user1->account); // 重新获取用户信息，因为上面的操作会修改用户信息。
$user  = $userTest->identifyTest($user1->account, sha1($user1->account . $user1->password . strtotime($user1->last)));
r($user) && p('account') && e('user1');                                  // 用户名存在，密码使用 sha1 加密，密码正确，返回用户。
r($user) && p('admin')   && e('~~');                                     // 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户不是超级管理员。
r($user) && p('avatar')  && e('/data/upload/1/202311/29144321062056c4'); // 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户头像是相对路径。

$oldUser = $user1;
$user1   = $userTest->getByIdTest($user1->account);
r(strtotime($user1->last) == $tester->server->request_time) && p() && e(1); // user1 用户的最后登录时间是服务器的请求时间。
r($user1->ip              == $tester->server->remote_addr)  && p() && e(1); // user1 用户的 IP 是服务器的远程地址。
r($user1->visits          == $oldUser->visits + 1)          && p() && e(1); // user1 用户的访问次数是原来的访问次数加 1。
