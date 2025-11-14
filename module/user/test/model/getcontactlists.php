#!/usr/bin/env php
<?php

/**

title=测试 userModel::getContactLists();
cid=19609

- 获取user1的联系人列表，取出数组第一个值第0条的listName属性 @联系人列表41
- 获取dev2的联系人列表，取出数组第三个值第2条的listName属性 @联系人列表2
- 获取test3的联系人列表的总数 @3
- 获取admin的联系人列表 @0
- 获取空用户的联系人列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';
su('admin');

$userContactList = zenData('usercontact');
$userContactList->gen(100);

$user = new userTest();
$user1ContactList = $user->getContactListsTest('user1', 'withempty|withnote');
$dev2ContactList  = $user->getContactListsTest('dev2', 'withempty');
$test3ContactList = $user->getContactListsTest('test3');
$adminContactList = $user->getContactListsTest('admin');
$emptyContactList = $user->getContactListsTest('');

r($user1ContactList)           && p('0:listName') && e('联系人列表41'); // 获取user1的联系人列表，取出数组第一个值
r($dev2ContactList)            && p('2:listName') && e('联系人列表2');  // 获取dev2的联系人列表，取出数组第三个值
r(count($test3ContactList))    && p() && e('3');      // 获取test3的联系人列表的总数
r($adminContactList)           && p() && e('0');      // 获取admin的联系人列表
r($emptyContactList)           && p() && e('0');      // 获取空用户的联系人列表
