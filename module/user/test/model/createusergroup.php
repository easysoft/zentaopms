#!/usr/bin/env php
<?php
/**
title=测试 userModel->createUserGroup);
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(1);
zdTable('company')->gen(1);
zdTable('usergroup')->gen(0);

su('admin');

$userTest = new userTest();

r($userTest->createUserGroupTest(array(),         'admin')) && p('result') && e(0); // 权限组为空，返回 false。
r($userTest->createUserGroupTest(array('1'),      ''))      && p('result') && e(0); // 用户名为空，返回 false。
r($userTest->createUserGroupTest(array('1', '2'), 'admin')) && p('result') && e(1); // 权限组和用户名都不为空，返回 true。

$groups = $tester->dao->select('*')->from(TABLE_USERGROUP)->fetchAll();
r(count($groups)) && p()                  && e(2);         // 创建了 2 个用户权限组。
r($groups)        && p('0:account,group') && e('admin,1'); // 第 1 个权限组的用户名是 admin，权限组是 1。
r($groups)        && p('1:account,group') && e('admin,2'); // 第 2 个权限组的用户名是 admin，权限组是 2。
