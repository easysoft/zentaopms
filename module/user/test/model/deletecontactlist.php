#!/usr/bin/env php
<?php
/**
title=测试 userModel->deleteContactList();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('usercontact');
$table->account->range('user1');
$table->gen(3);

$userTest = new userTest();

$lists = $userTest->getContactListsTest('user1');
r(count($lists)) && p()       && e(3); //联系人列表数量为 3。
r($lists)        && p('0:id') && e(3); //联系人列表第 1 条 id 为 3。
r($lists)        && p('1:id') && e(2); //联系人列表第 2 条 id 为 2。
r($lists)        && p('2:id') && e(1); //联系人列表第 3 条 id 为 1。

r($userTest->deleteContactListTest(4)) && p('result') && e(1); //删除 id 为 4 的联系人列表。
r($userTest->deleteContactListTest(3)) && p('result') && e(1); //删除 id 为 3 的联系人列表。
r($userTest->deleteContactListTest(2)) && p('result') && e(1); //删除 id 为 2 的联系人列表。

$lists = $userTest->getContactListsTest('user1');
r(count($lists)) && p()       && e(1); //联系人列表数量为 1。
r($lists)        && p('0:id') && e(1); //联系人列表第 1 条 id 为 1。
