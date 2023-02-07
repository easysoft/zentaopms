#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$usercontact = zdTable('usercontact');
$usercontact->gen(5);

/**

title=测试 userModel->deleteContactList();
cid=1
pid=1

删除ID为1的联系人列表 >> 0
删除ID为2的联系人列表 >> 0
删除ID为null的联系人列表 >> 0
删除ID为1000的联系人列表 >> 0

*/
$user = new userTest();

r($user->deleteContactListTest(1))    && p('listName')  && e('0'); //删除ID为1的联系人列表
r($user->deleteContactListTest(2))    && p('listName')  && e('0'); //删除ID为2的联系人列表
r($user->deleteContactListTest(null)) && p('userLsit')  && e('0'); //删除ID为null的联系人列表
r($user->deleteContactListTest(1000)) && p('userList')  && e('0'); //删除ID为1000的联系人列表
