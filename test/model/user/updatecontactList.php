#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
$db->switchDB();
su('admin');

/**

title=测试 userModel->updateContactList();
cid=1
pid=1

更新ID为1的联系人列表，获取更新的联系人列表名称 >> 新的列表名称1
更新ID为2的联系人列表，获取更新的联系人列表名称 >> 新的列表名称2
更新ID为1000的联系人列表，获取更新的联系人列表 >> 0

*/
$user = new userTest();
$userList = array('admin,test2,user29,assfjg');

r($user->updateContactListTest(1,     '新的列表名称1', $userList)) && p('listName')  && e('新的列表名称1'); //更新ID为1的联系人列表，获取更新的联系人列表名称
r($user->updateContactListTest(2,     '新的列表名称2', $userList)) && p('listName')  && e('新的列表名称2'); //更新ID为2的联系人列表，获取更新的联系人列表名称
r($user->updateContactListTest(3,     '新的列表名称3', ''))        && p('userLsit')  && e('');              //更新ID为3的联系人列表，获取更新的联系人列表
r($user->updateContactListTest(1000,  '新的列表名称4', $userList)) && p('userList')  && e('0');             //更新ID为1000的联系人列表，获取更新的联系人列表

$db->restoreDB();