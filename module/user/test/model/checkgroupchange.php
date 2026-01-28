#!/usr/bin/env php
<?php

/**

title=测试 userModel->checkGroupChange();
cid=19585

- 新权限组和旧权限组相同，返回 false。属性result @0
- 查看用户权限组，返回 2 条记录。 @2
- 第 1 条记录的用户名是 user1，权限组 id 是 1。
 - 第0条的account属性 @user1
 - 第0条的group属性 @1
- 第 2 条记录的用户名是 user1，权限组 id 是 2。
 - 第1条的account属性 @user1
 - 第1条的group属性 @2
- 新权限组和旧权限组不同，返回 true。属性result @1
- 查看用户权限组，返回 2 条记录。 @2
- 第 1 条记录的用户名是 user1，权限组 id 是 2。
 - 第0条的account属性 @user1
 - 第0条的group属性 @2
- 第 2 条记录的用户名是 user1，权限组 id 是 3。
 - 第1条的account属性 @user1
 - 第1条的group属性 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
zenData('group')->gen(3);

$groupTable = zenData('usergroup');
$groupTable->account->range('user1');
$groupTable->group->range('1,2');
$groupTable->gen(2);

su('admin');

$userTest = new userModelTest();

$user = $userTest->getByIdTest('user1');
$user->group = array(1, 2);

r($userTest->checkGroupChangeTest($user)) && p('result') && e(0); // 新权限组和旧权限组相同，返回 false。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user1,1'); // 第 1 条记录的用户名是 user1，权限组 id 是 1。
r($groups)        && p('1:account,group') && e('user1,2'); // 第 2 条记录的用户名是 user1，权限组 id 是 2。

$user->group = array(0, 2, 3);
r($userTest->checkGroupChangeTest($user)) && p('result') && e(1); // 新权限组和旧权限组不同，返回 true。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 查看用户权限组，返回 2 条记录。
r($groups)        && p('0:account,group') && e('user1,2'); // 第 1 条记录的用户名是 user1，权限组 id 是 2。
r($groups)        && p('1:account,group') && e('user1,3'); // 第 2 条记录的用户名是 user1，权限组 id 是 3。
