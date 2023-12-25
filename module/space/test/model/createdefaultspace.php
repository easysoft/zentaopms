#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->createDefaultSpace();
cid=1

- 测试传入空用户名创建默认空间
 - 属性name @默认空间
 - 属性k8space @quickon-app
 - 属性owner @admin
 - 属性default @1
- 测试传入已存在的用户名创建默认空间
 - 属性name @默认空间
 - 属性k8space @quickon-app
 - 属性owner @user1
 - 属性default @1
- 测试传入不存在的用户名创建默认空间
 - 属性name @默认空间
 - 属性k8space @quickon-app
 - 属性owner @test
 - 属性default @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/space.class.php';

zdTable('user')->gen(5);
zdTable('space')->gen(0);

$accounts = array('', 'user1', 'test');

$spaceTester = new spaceTest();
r($spaceTester->createDefaultSpaceTest($accounts[0])) && p('name,k8space,owner,default') && e('默认空间,quickon-app,admin,1'); // 测试传入空用户名创建默认空间
r($spaceTester->createDefaultSpaceTest($accounts[1])) && p('name,k8space,owner,default') && e('默认空间,quickon-app,user1,1'); // 测试传入已存在的用户名创建默认空间
r($spaceTester->createDefaultSpaceTest($accounts[2])) && p('name,k8space,owner,default') && e('默认空间,quickon-app,test,1'); // 测试传入不存在的用户名创建默认空间
