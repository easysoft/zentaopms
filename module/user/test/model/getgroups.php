#!/usr/bin/env php
<?php

/**

title=测试 userModel->getGroups();
cid=19611

- 参数为空字符串，返回空数组。 @0
- 参数为不存在的用户名，返回空数组。 @0
- user1 用户属于 1,2,3 组。
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
- user2 用户属于 2,3 组。
 - 属性2 @2
 - 属性3 @3
- user3 用户属于 3,4 组。
 - 属性3 @3
 - 属性4 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('usergroup');
$table->account->range('user1{3},user2{2},user3{2}');
$table->group->range('1-3,2,3,3,4');
$table->gen(7);

$userTest = new userModelTest();

r($userTest->getGroupsTest(''))      && p()        && e(0);       // 参数为空字符串，返回空数组。
r($userTest->getGroupsTest('admin')) && p()        && e(0);       // 参数为不存在的用户名，返回空数组。
r($userTest->getGroupsTest('user1')) && p('1,2,3') && e('1,2,3'); // user1 用户属于 1,2,3 组。
r($userTest->getGroupsTest('user2')) && p('2,3')   && e('2,3');   // user2 用户属于 2,3 组。
r($userTest->getGroupsTest('user3')) && p('3,4')   && e('3,4');   // user3 用户属于 3,4 组。
