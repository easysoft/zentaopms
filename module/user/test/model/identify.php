#!/usr/bin/env php
<?php

/**

title=测试 userModel->identify();
timeout=0
cid=19640

- 用户名不为空，密码为空，返回 false。 @0
- 用户名为空，密码不为空，返回 false。 @0
- 用户名不合法，密码不为空，返回 false。 @0
- 用户名不存在，密码不为空，返回 false。 @0
- 用户名存在，密码使用 md5 加密，密码不正确，返回 false。 @0
- 用户名存在，密码使用 sha1 加密，密码不正确，返回 false。 @0
- 用户名存在，密码使用明文，密码不正确，返回 false。 @0
- 用户名存在，密码使用明文，密码正确，返回用户。属性account @admin
- 用户名存在，密码使用 md5 加密，密码正确，返回用户。属性account @admin
- 用户名存在，密码使用 sha1 加密，密码正确，返回用户。属性account @admin
- 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户是超级管理员。属性admin @1
- 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户头像是相对路径。属性avatar @/data/upload/1/202311/29144321062056c4
- admin 用户的 IP 是服务器的远程地址。 @1
- admin 用户的访问次数是原来的访问次数加 1。 @1
- admin 用户的访问次数不变。 @1
- admin 用户的访问次数不变。 @1
- 用户名存在，密码使用明文，密码正确，返回用户。属性account @user1
- 用户名存在，密码使用 md5 加密，密码正确，返回用户。属性account @user1
- 用户名存在，密码使用 sha1 加密，密码正确，返回用户。属性account @user1
- 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户不是超级管理员。属性admin @~~
- 用户名存在，密码使用 sha1 加密，密码正确，返回用户，用户头像是相对路径。属性avatar @/data/upload/1/202311/29144321062056c4
- user1 用户的 IP 是服务器的远程地址。 @1
- user1 用户的访问次数是原来的访问次数加 1。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $config, $tester;

$tester->dao->delete()->from(TABLE_USER)->exec();
$tester->dao->delete()->from(TABLE_COMPANY)->exec();

$table = zenData('user');
$table->password->range(md5(123456));
$table->avatar->range('/www/data/upload/1/202311/29144321062056c4');
$table->last->prefix('2025-01-01 10:00:00');
$table->gen(2);

$company = zenData('company');
$company->id->range('1');
$company->admins->range(',admin,');
$company->gen(1);

su('admin');

$config->webRoot      = '/';
$app->company         = $tester->dao->select('*')->from(TABLE_COMPANY)->fetch();
$app->company->admins = ',admin,';

$userTest = new userModelTest();

$random = updateSessionRandom();
$admin  = $userTest->getByIdTest('admin');
$user1  = $userTest->getByIdTest('user1');

r($userTest->identifyTest('admin', ''))           && p() && e(0);
r($userTest->identifyTest('', '123456'))          && p() && e(0);
r($userTest->identifyTest('a1_~!', '123456'))     && p() && e(0);
r($userTest->identifyTest('user2', '123456'))     && p() && e(0);
r($userTest->identifyTest('admin', md5(123456)))  && p() && e(0);
r($userTest->identifyTest('admin', sha1(123456))) && p() && e(0);
r($userTest->identifyTest('admin', '654321'))     && p() && e(0);

r($userTest->identifyTest($admin->account, '123456'))                        && p('account') && e('admin');
r($userTest->identifyTest($admin->account, md5($admin->password . $random))) && p('account') && e('admin');

$admin = $userTest->getByIdTest($admin->account);
$user  = $userTest->identifyTest($admin->account, sha1($admin->account . $admin->password . strtotime($admin->last)));
r($user) && p('account') && e('admin');
r($user) && p('admin')   && e(1);
r($user) && p('avatar')  && e('/data/upload/1/202311/29144321062056c4');

$oldAdmin = $admin;
$admin    = $userTest->getByIdTest($admin->account);
r($admin->ip     == $tester->server->remote_addr) && p() && e(1);
r($admin->visits == $oldAdmin->visits + 1)        && p() && e(1);

$app->installing = true;
$oldAdmin = $admin;
$admin    = $userTest->getByIdTest($admin->account);
r($admin->visits == $oldAdmin->visits) && p() && e(1);

$app->upgrading = true;
$oldAdmin = $admin;
$admin    = $userTest->getByIdTest($admin->account);
r($admin->visits == $oldAdmin->visits) && p() && e(1);

$app->installing = false;
$app->upgrading  = false;
r($userTest->identifyTest($user1->account, '123456'))                        && p('account') && e('user1');
r($userTest->identifyTest($user1->account, md5($user1->password . $random))) && p('account') && e('user1');

$user1 = $userTest->getByIdTest($user1->account);
$user  = $userTest->identifyTest($user1->account, sha1($user1->account . $user1->password . strtotime($user1->last)));
r($user) && p('account') && e('user1');
r($user) && p('admin')   && e('~~');
r($user) && p('avatar')  && e('/data/upload/1/202311/29144321062056c4');

$oldUser = $user1;
$user1   = $userTest->getByIdTest($user1->account);
r($user1->ip     == $tester->server->remote_addr) && p() && e(1);
r($user1->visits == $oldUser->visits + 1)         && p() && e(1);
