#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getListByAccount();
cid=1
pid=1

获取admin的联系人列表 >> 0
获取admin的联系人列表 >> 0
获取admin的联系人列表 >> 0

*/
$user = new userTest();
$adminContactList = $user->getListByAccountTest('admin');
$test2ContactList = $user->getListByAccountTest('test2');
$emptyContactList = $user->getListByAccountTest('');

r($adminContactList) && p() && e('0'); // 获取admin的联系人列表
r($test2ContactList) && p() && e('0'); // 获取admin的联系人列表
r($emptyContactList) && p() && e('0'); // 获取admin的联系人列表