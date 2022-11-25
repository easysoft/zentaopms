#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getContactLists();
cid=1
pid=1

获取admin的联系人列表 >> 0

*/
$user = new userTest();
$adminContactList1 = $user->getContactListsTest('admin', 'withempty|withnote');
$adminContactList2 = $user->getContactListsTest('admin', '');
$test2ContactList  = $user->getContactListsTest('test2');
$emptyContactList  = $user->getContactListsTest('', '');

r($adminContactList) && p() && e('0'); // 获取admin的联系人列表