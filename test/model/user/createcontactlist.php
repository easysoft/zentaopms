#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$usercontact = zdTable('usercontact');
$usercontact->gen(1);

/**

title=测试 userModel->createContactList();
cid=1
pid=1

插入一条联系人列表，获取插入的联系人列表名称 >> 新的列表名称1
插入一条联系人列表，获取插入的联系人列表     >> admin,test2,user29,assfjg
列表名称重复时                               >> 『列表名称』已经有『新的列表名称1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
列表名称为空时                               >> 1

*/

$userTester = new userTest();
$userList = array('admin,test2,user29,assfjg');

r($userTester->createContactListTest('新的列表名称1', $userList)) && p('listName')             && e('新的列表名称1');                                                                                          // 插入一条联系人列表，获取插入的联系人列表名称
r($userTester->createContactListTest('新的列表名称2', $userList)) && p('userList')             && e('admin,test2,user29,assfjg');                                                                              // 插入一条联系人列表，获取插入的联系人列表名称
r($userTester->createContactListTest('新的列表名称1', array()))   && p('message[listName]:0')  && e('『列表名称』已经有『新的列表名称1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 列表名称重复时
r($userTester->createContactListTest('',              array()))   && p('message[listName]:0')  && e('1');                                                                                                      // 列表名称为空时
