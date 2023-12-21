#!/usr/bin/env php
<?php
/**
title=测试 userModel->createContactList();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('usercontact')->gen(1);

$userTest = new userTest();

$contact1 = (object)array('account' => 'user1', 'listName' => '',            'userList' => '',            'public' => 0);
$contact2 = (object)array('account' => 'user1', 'listName' => '联系人列表1', 'userList' => 'user1,user2', 'public' => 0);
$contact3 = (object)array
(
    'account' => '这是一个很长的创建人名称。这个很长的创建人名称到底有多长呢？这个很长的创建人名称长到超出了数据库字段的长度限制。',
    'listName' => '这是一个很长的联系人列表名称。这个很长的联系人列表名称到底有多长呢？这个很长的联系人列表名称长到超出了数据库字段的长度限制。',
    'userList' => 'user1,user2',
    'public' => 'public'
);
$contact4 = (object)array('account' => 'user1', 'listName' => '联系人列表2', 'userList' => 'user1,user2', 'public' => 0);

/* 测试必填项为空的情况。*/
$result = $userTest->createContactListTest($contact1);
r($result) && p('result')          && e(0);                        // 列表名称和用户列表为空，返回 false。
r($result) && p('errors:account')  && e('``');                     // 创建人无错误提示。
r($result) && p('errors:listName') && e('『列表名称』不能为空。'); // 列表名称不能为空。
r($result) && p('errors:userList') && e('『用户列表』不能为空。'); // 用户列表不能为空。
r($result) && p('errors:public')   && e('``');                     // 公共联系人无错误提示。

/* 测试列表名称已存在的情况。*/
$result = $userTest->createContactListTest($contact2);
r($result) && p('result')          && e(0);    // 列表名称已存在，返回 false。
r($result) && p('errors:account')  && e('``'); // 创建人无错误提示。
r($result) && p('errors:listName') && e('『列表名称』已经有『联系人列表1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 列表名称已存在。
r($result) && p('errors:userList') && e('``'); // 用户列表无错误提示。
r($result) && p('errors:public')   && e('``'); // 公共联系人无错误提示。

/* 测试数据格式不符合数据库字段设置的情况。*/
$result = $userTest->createContactListTest($contact3);
r($result) && p('result')          && e(0);                                                 // 创建人和列表名称过长，返回 false。
r($result) && p('errors:account')  && e('『创建人』长度应当不超过『30』，且大于『0』。');   // 创建人过长。
r($result) && p('errors:listName') && e('『列表名称』长度应当不超过『60』，且大于『0』。'); // 列表名称过长。
r($result) && p('errors:userList') && e('``');                                              // 用户列表无错误提示。
r($result) && p('errors:public')   && e('『公共联系人』应当是数字。');                      // 公共联系人应当是数字。

/* 测试创建成功的情况。*/
$result = $userTest->createContactListTest($contact4);
r($result) && p('result')          && e(1);    // 创建成功，返回 true。
r($result) && p('errors:account')  && e('``'); // 创建人无错误提示。
r($result) && p('errors:listName') && e('``'); // 列表名称无错误提示。
r($result) && p('errors:userList') && e('``'); // 用户列表无错误提示。
r($result) && p('errors:public')   && e('``'); // 公共联系人无错误提示。
