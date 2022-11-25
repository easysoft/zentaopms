#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
$db->switchDB();
su('admin');

/**

title=测试 userModel->createContactList();
cid=1
pid=1

插入一条联系人列表，获取插入的联系人列表名称 >> 新的列表名称1
插入一条联系人列表，获取插入的联系人列表名称 >> 新的列表名称2

*/
$user = new userTest();
$userList = array('admin,test2,user29,assfjg');

r($user->createContactListTest('新的列表名称1', $userList)) && p('listName')  && e('新的列表名称1'); //插入一条联系人列表，获取插入的联系人列表名称
r($user->createContactListTest('新的列表名称2', $userList)) && p('listName')  && e('新的列表名称2'); //插入一条联系人列表，获取插入的联系人列表名称
r($user->createContactListTest('新的列表名称3', array()))   && p('userList')  && e('');              //插入一条联系人列表，获取插入的联系人列表

$db->restoreDB();