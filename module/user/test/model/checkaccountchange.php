#!/usr/bin/env php
<?php

/**

title=测试 userModel->checkAccountChange();
timeout=0
cid=19581

- 新用户忙和旧用户名相同，返回 false。属性result @0
- 查看用户权限组，返回 2 条记录。 @2
- 第 1 条记录的用户名是 admin，权限组 id 是 1。
 - 第0条的account属性 @admin
 - 第0条的group属性 @1
- 第 2 条记录的用户名是 admin，权限组 id 是 2。
 - 第1条的account属性 @admin
 - 第1条的group属性 @2
- 查看用户视图，返回 1 条记录。 @1
- 第 1 条记录的用户名是 admin。第0条的account属性 @admin
- 数据库中超级管理员是 admin。 @,admin,

- $app 对象中超级管理员是 admin。 @,admin,

- 新用户忙和旧用户名不同，返回 true。属性result @1
- 查看用户权限组，返回 2 条记录。 @2
- 第 1 条记录的用户名是 user1，权限组 id 是 1。
 - 第0条的account属性 @user1
 - 第0条的group属性 @1
- 第 2 条记录的用户名是 user1，权限组 id 是 2。
 - 第1条的account属性 @user1
 - 第1条的group属性 @2
- 查看用户视图，返回 1 条记录。 @1
- 第 1 条记录的用户名是 user1。第0条的account属性 @user1
- 数据库中超级管理员是 user1。 @,user1,

- $app 对象中超级管理员是 user1。 @,user1,

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);
zenData('company')->gen(1);

$viewTable = zenData('userview');
$viewTable->account->range('admin');
$viewTable->gen(1);

$groupTable = zenData('usergroup');
$groupTable->account->range('admin');
$groupTable->group->range('1,2');
$groupTable->gen(2);

su('admin');

global $app;

$app->company->admins = ',admin,';

$userTest = new userModelTest();

r($userTest->checkAccountChangeTest('admin', 'admin')) && p('result') && e(0); // 新用户忙和旧用户名相同，返回 false。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('admin,1'); // 第 1 条记录的用户名是 admin，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('admin,2'); // 第 2 条记录的用户名是 admin，权限组 id 是 2。

$views = $tester->dao->select('*')->from(TABLE_USERVIEW)->fetchAll();
r(count($views)) && p()            && e(1);       // 查看用户视图，返回 1 条记录。
r($views)        && p('0:account') && e('admin'); // 第 1 条记录的用户名是 admin。

$admins = $tester->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq(1)->fetch('admins');
r($admins) && p() && e(',admin,'); // 数据库中超级管理员是 admin。

r($app->company->admins) && p() && e(',admin,'); // $app 对象中超级管理员是 admin。

r($userTest->checkAccountChangeTest('admin', 'user1')) && p('result') && e(1); // 新用户忙和旧用户名不同，返回 true。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user1,1'); // 第 1 条记录的用户名是 user1，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user1,2'); // 第 2 条记录的用户名是 user1，权限组 id 是 2。

$views = $tester->dao->select('*')->from(TABLE_USERVIEW)->fetchAll();
r(count($views)) && p()            && e(1);        // 查看用户视图，返回 1 条记录。
r($views)        && p('0:account') && e('user1');  // 第 1 条记录的用户名是 user1。

$admins = $tester->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq(1)->fetch('admins');
r($admins) && p() && e(',user1,'); // 数据库中超级管理员是 user1。

r($app->company->admins) && p() && e(',user1,'); // $app 对象中超级管理员是 user1。