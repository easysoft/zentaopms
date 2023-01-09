#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$usercontact = zdTable('usercontact');
$usercontact->gen(3);

/**

title=测试 userModel->updateContactList();
cid=1
pid=1

更新ID为1的联系人列表，获取更新的联系人列表名称 >> 新的列表名称1
更新ID为2的联系人列表，获取更新的联系人列表     >> admin,test2,user29,assfjg
列表名称为空时                                  >> 『列表名称』不能为空。

*/
$userTester = new userTest();
$userList = array('admin,test2,user29,assfjg');

r($userTester->updateContactListTest(1, '新的列表名称1', $userList)) && p('listName')             && e('新的列表名称1');          //更新ID为1的联系人列表，获取更新的联系人列表名称
r($userTester->updateContactListTest(2, '新的列表名称2', $userList)) && p('userList')             && e('新的列表名称2');          //更新ID为2的联系人列表，获取更新的联系人列表名称
r($userTester->updateContactListTest(1, '',              array()))   && p('message[listName]:0')  && e('『列表名称』不能为空。'); //列表名称为空时                                                                                                     // 列表名称为空时
