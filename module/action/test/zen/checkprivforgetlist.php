#!/usr/bin/env php
<?php

/**

title=测试 actionZen::checkPrivForGetList();
timeout=0

- 普通用户可以查看自己的用户历史 @1
- 普通用户不能查看管理员用户历史 @0
- 管理员可以查看普通用户历史 @1
- 管理员可以查看未注册类型历史 @1
- 管理员可以查看不存在的对象历史 @1
- 管理员仍不能使用非法对象类型 @0
- 已注册且存在的内置对象默认放行 @1
- 页面可显示但无对象表映射的类型有历史时放行 @1
- 页面可显示但无对象表映射的类型无历史时也放行 @1
- 未注册的对象类型拒绝 @0
- 已注册但不存在的对象拒绝 @0
- 非法对象类型拒绝 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_ACTION)->where('objectID')->in(array(99019, 99020, 99021, 99022))->exec();
$tester->dao->delete()->from(TABLE_ACTION)->where('id')->in(array(99019, 99020, 99021, 99022))->exec();
$tester->dao->delete()->from(TABLE_TODO)->where('id')->eq(99019)->exec();

$tester->dao->insert(TABLE_TODO)
    ->set('id')->eq(99019)
    ->set('account')->eq('user1')
    ->set('date')->eq('2026-10-28')
    ->set('begin')->eq('0800')
    ->set('end')->eq('1700')
    ->set('type')->eq('custom')
    ->set('name')->eq('unit-vuln019-history')
    ->set('status')->eq('wait')
    ->set('config')->eq('')
    ->set('assignedTo')->eq('user1')
    ->set('assignedBy')->eq('admin')
    ->set('vision')->eq('rnd')
    ->set('deleted')->eq(0)
    ->exec();

$tester->dao->insert(TABLE_ACTION)->set('id')->eq(99019)->set('objectType')->eq('todo')->set('objectID')->eq(99019)->set('actor')->eq('user1')->set('action')->eq('opened')->set('date')->eq(helper::now())->set('vision')->eq('rnd')->exec();
$tester->dao->insert(TABLE_ACTION)->set('id')->eq(99020)->set('objectType')->eq('codescanrule')->set('objectID')->eq(99020)->set('actor')->eq('user1')->set('action')->eq('opened')->set('date')->eq(helper::now())->set('vision')->eq('rnd')->exec();
$tester->dao->insert(TABLE_ACTION)->set('id')->eq(99021)->set('objectType')->eq('unregisteredhistoryobject')->set('objectID')->eq(99021)->set('actor')->eq('user1')->set('action')->eq('opened')->set('date')->eq(helper::now())->set('vision')->eq('rnd')->exec();

$user1ID = (int)$tester->dao->select('id')->from(TABLE_USER)->where('account')->eq('user1')->fetch('id');
$adminID = (int)$tester->dao->select('id')->from(TABLE_USER)->where('account')->eq('admin')->fetch('id');

su('user1');
$actionTest = new actionZenTest();

r($actionTest->checkPrivForGetListTest('user', $user1ID)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('user', $adminID)) && p() && e('0');
r($actionTest->checkPrivForGetListTest('todo', 99019)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('codescanrule', 99020)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('codescanrule', 99022)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('unregisteredhistoryobject', 99021)) && p() && e('0');
r($actionTest->checkPrivForGetListTest('todo', 99023)) && p() && e('0');
r($actionTest->checkPrivForGetListTest('../bad-type', 99019)) && p() && e('0');

su('admin');
$actionTest = new actionZenTest();
r($actionTest->checkPrivForGetListTest('user', $user1ID)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('codescanrule', 99020)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('codescanrule', 99022)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('unregisteredhistoryobject', 99021)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('todo', 99023)) && p() && e('1');
r($actionTest->checkPrivForGetListTest('../bad-type', 99019)) && p() && e('0');

$tester->dao->delete()->from(TABLE_ACTION)->where('objectID')->in(array(99019, 99020, 99021, 99022))->exec();
$tester->dao->delete()->from(TABLE_ACTION)->where('id')->in(array(99019, 99020, 99021, 99022))->exec();
$tester->dao->delete()->from(TABLE_TODO)->where('id')->eq(99019)->exec();
