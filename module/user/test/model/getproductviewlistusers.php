#!/usr/bin/env php
<?php
/**

title=测试 userModel->getProductViewListUsers();
cid=1
pid=1

- 获取ID为11的产品关联的用户。
 - 属性po1 @po1
 - 属性test11 @test11
 - 属性dev11 @dev11
 - 属性admin @admin
 - 属性user1 @user1
- 获取ID为12的产品关联的用户。
 - 属性po2 @po2
 - 属性test12 @test12
 - 属性dev12 @dev12
 - 属性admin @admin
 - 属性user1 @user1
 - 属性user2 @user2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

dao::$cache = array();
zdTable('user')->gen(10);
zdTable('group')->config('group')->gen(100);
zdTable('usergroup')->config('usergroup')->gen(100);
zdTable('grouppriv')->config('grouppriv')->gen(100);
zdTable('userview')->config('userview')->gen(10);
zdTable('team')->config('team')->gen(200);
zdTable('acl')->config('acl')->gen(100);
zdTable('stakeholder')->config('stakeholder')->gen(100);

zdTable('product')->gen(20);
zdTable('projectadmin')->config('projectadmin')->gen(30);

$tester->loadModel('product');
$product = $tester->user->fetchByID(11, 'product');
r($tester->user->getProductViewListUsers($product)) && p('po1,test11,dev11,admin,user1')       && e('po1,test11,dev11,admin,user1');       //获取ID为11的产品关联的用户。

$product = $tester->user->fetchByID(12, 'product');
r($tester->user->getProductViewListUsers($product)) && p('po2,test12,dev12,admin,user1,user2') && e('po2,test12,dev12,admin,user1,user2'); //获取ID为12的产品关联的用户。
