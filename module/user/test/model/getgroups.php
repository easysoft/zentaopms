#!/usr/bin/env php
<?php
/**
title=测试 userModel->getGroups();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('usergroup');
$table->account->range('user1{3},user2{2},user3{2}');
$table->group->range('1-3,2,3,3,4');
$table->gen(7);

$userTest = new userTest();

r($userTest->getGroupsTest(''))      && p()        && e(0);       // 参数为空字符串，返回空数组。
r($userTest->getGroupsTest('admin')) && p()        && e(0);       // 参数为不存在的用户名，返回空数组。
r($userTest->getGroupsTest('user1')) && p('1,2,3') && e('1,2,3'); // user1 用户属于 1,2,3 组。
r($userTest->getGroupsTest('user2')) && p('2,3')   && e('2,3');   // user2 用户属于 2,3 组。
r($userTest->getGroupsTest('user3')) && p('3,4')   && e('3,4');   // user3 用户属于 3,4 组。
