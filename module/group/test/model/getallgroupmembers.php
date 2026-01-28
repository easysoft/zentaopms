#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getAllGroupMembers();
timeout=0
cid=16701

- 步骤1：验证group1的正常成员属性1 @user1|user2|user7|user8
- 步骤2：验证group2的成员分布属性2 @user3|user4
- 步骤3：验证项目管理员分组属性5 @admin|user1|user2
- 步骤4：验证group3的成员属性3 @user5|user6
- 步骤5：验证空分组4没有成员属性4 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,deleted1,deleted2');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,已删除1,已删除2');
$userTable->deleted->range('0{9},1{2}');
$userTable->visions->range('rnd{11}');
$userTable->gen(11);

$groupTable = zenData('group');
$groupTable->id->range('1-10');
$groupTable->vision->range('rnd{10}');
$groupTable->name->range('GROUP1,GROUP2,GROUP3,EMPTY,PROJECTADMIN,GROUP6,GROUP7,GROUP8,GROUP9,GROUP10');
$groupTable->role->range('group1,group2,group3,empty,projectAdmin,group6,group7,group8,group9,group10');
$groupTable->gen(10);

$usergroupTable = zenData('usergroup');
$usergroupTable->account->range('user1,user2,user3,user4,user5,user6,user7,user8,deleted1,deleted2');
$usergroupTable->group->range('1{2},2{2},3{2},1{2},2{2}');
$usergroupTable->gen(10);

$projectAdminTable = zenData('projectadmin');
$projectAdminTable->account->range('admin,user1,user2');
$projectAdminTable->gen(3);

su('admin');

$groupTest = new groupModelTest();

r($groupTest->getAllGroupMembersTest()) && p('1') && e('user1|user2|user7|user8'); // 步骤1：验证group1的正常成员
r($groupTest->getAllGroupMembersTest()) && p('2') && e('user3|user4'); // 步骤2：验证group2的成员分布
r($groupTest->getAllGroupMembersTest()) && p('5') && e('admin|user1|user2'); // 步骤3：验证项目管理员分组
r($groupTest->getAllGroupMembersTest()) && p('3') && e('user5|user6'); // 步骤4：验证group3的成员
r($groupTest->getAllGroupMembersTest()) && p('4') && e('~~'); // 步骤5：验证空分组4没有成员