#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->defaultSpace();
cid=1

- 获取用户名为空的默认空间
 - 属性name @默认空间
 - 属性k8space @quickon-app
 - 属性owner @admin
 - 属性default @1
- 获取用户名为user1的默认空间
 - 属性name @空间2
 - 属性k8space @quickon-app
 - 属性owner @user1
 - 属性default @1
- 获取用户名不存在的默认空间
 - 属性name @默认空间
 - 属性k8space @quickon-app
 - 属性owner @test
 - 属性default @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/space.class.php';

zdTable('user')->gen(5);
zdTable('space')->config('space')->gen(5);

$accounts = array('', 'user1', 'test');

$spaceTester = new spaceTest();
r($spaceTester->defaultSpaceTest($accounts[0])) && p('name,k8space,owner,default') && e('默认空间,quickon-app,admin,1'); // 获取用户名为空的默认空间
r($spaceTester->defaultSpaceTest($accounts[1])) && p('name,k8space,owner,default') && e('空间2,quickon-app,user1,1');    // 获取用户名为user1的默认空间
r($spaceTester->defaultSpaceTest($accounts[2])) && p('name,k8space,owner,default') && e('默认空间,quickon-app,test,1');  // 获取用户名不存在的默认空间
