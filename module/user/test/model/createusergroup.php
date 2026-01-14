#!/usr/bin/env php
<?php

/**

title=测试 userModel->createUserGroup();
cid=19599

- 权限组为空，返回 false。属性result @0
- 用户名为空，返回 false。属性result @0
- 权限组和用户名都不为空，返回 true。属性result @1
- 创建了 2 个用户权限组。 @2
- 第 1 个权限组的用户名是 admin，权限组是 1。
 - 第0条的account属性 @admin
 - 第0条的group属性 @1
- 第 2 个权限组的用户名是 admin，权限组是 2。
 - 第1条的account属性 @admin
 - 第1条的group属性 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);
zenData('company')->gen(1);
zenData('usergroup')->gen(0);

su('admin');

$userTest = new userModelTest();

r($userTest->createUserGroupTest(array(),         'admin')) && p('result') && e(0); // 权限组为空，返回 false。
r($userTest->createUserGroupTest(array('1'),      ''))      && p('result') && e(0); // 用户名为空，返回 false。
r($userTest->createUserGroupTest(array('1', '2'), 'admin')) && p('result') && e(1); // 权限组和用户名都不为空，返回 true。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 创建了 2 个用户权限组。
r($groups)        && p('0:account,group') && e('admin,1'); // 第 1 个权限组的用户名是 admin，权限组是 1。
r($groups)        && p('1:account,group') && e('admin,2'); // 第 2 个权限组的用户名是 admin，权限组是 2。
