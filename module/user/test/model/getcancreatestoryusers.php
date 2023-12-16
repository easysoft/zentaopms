#!/usr/bin/env php
<?php
/**
title=测试 userModel->getCanCreateStoryUsers();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('company')->gen(1);

$userTable = zdTable('user');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->realname->range('admin,user1,user2,user3,user4,user5,user6,user7,``{2}');
$userTable->deleted->range('0{9},1');
$userTable->gen(10);

$groupTable = zdTable('usergroup');
$groupTable->account->range('user1,user1,user2,user2,user3,user4,user4,user5,user6,user6');
$groupTable->group->range('1,2,2,3,3,3,4,5,6,7');
$groupTable->gen(10);

$privTable = zdTable('grouppriv');
$privTable->group->range('1-5{6}');
$privTable->module->range('story{6},task{6},bug{6},story{6},story{6}');
$privTable->method->range('create,batchCreate,edit,batchEdit,delete,batchDelete');
$privTable->gen(30);

$userTest = new userTest();

$users = $userTest->getCanCreateStoryUsersTest();
r(count($users)) && p()        && e(4);         // 有创建需求权限的用户数为 4。
r($users)        && p('admin') && e('A:admin'); // admin 用户是超级管理员，有创建需求权限。
r($users)        && p('user1') && e('U:user1'); // user1 用户在权限组 1 中，有创建需求权限。
r($users)        && p('user2') && e('~~');      // user2 用户在权限组 2 和 3 中，没有创建需求权限。
r($users)        && p('user3') && e('~~');      // user3 用户在权限组 3 中，没有创建需求权限。
r($users)        && p('user4') && e('U:user4'); // user4 用户在权限组 3 和 4 中，有创建需求权限。
r($users)        && p('user5') && e('U:user5'); // user5 用户在权限组 5 中，有创建需求权限。
r($users)        && p('user6') && e('~~');      // user6 用户在权限组 6 中，没有创建需求权限。
r($users)        && p('user7') && e('~~');      // user7 用户不在权限组中，没有创建需求权限。
r($users)        && p('user8') && e('~~');      // user8 用户真实姓名为空，没有创建需求权限。
r($users)        && p('user9') && e('~~');      // user9 用户被删除，没有创建需求权限。
