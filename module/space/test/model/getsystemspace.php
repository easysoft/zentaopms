#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->getSystemSpace();
cid=1

- 获取用户名为空的系统空间
 - 属性name @空间1
 - 属性k8space @quickon-system
 - 属性owner @admin
 - 属性default @0
- 获取用户名为admin的系统空间
 - 属性name @空间1
 - 属性k8space @quickon-system
 - 属性owner @admin
 - 属性default @0
- 获取用户名为user1的系统空间
 - 属性name @系统空间
 - 属性k8space @quickon-system
 - 属性owner @user1
 - 属性default @0
- 获取用户名不存在的系统空间
 - 属性name @系统空间
 - 属性k8space @quickon-system
 - 属性owner @test
 - 属性default @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/space.class.php';

zdTable('user')->gen(5);
zdTable('space')->config('space')->gen(5);

$owners = array('', 'admin', 'user1', 'test');

$spaceTester = new spaceTest();
r($spaceTester->getSystemSpaceTest($owners[0])) && p('name,k8space,owner,default') && e('空间1,quickon-system,admin,0');    // 获取用户名为空的系统空间
r($spaceTester->getSystemSpaceTest($owners[1])) && p('name,k8space,owner,default') && e('空间1,quickon-system,admin,0');    // 获取用户名为admin的系统空间
r($spaceTester->getSystemSpaceTest($owners[2])) && p('name,k8space,owner,default') && e('系统空间,quickon-system,user1,0'); // 获取用户名为user1的系统空间
r($spaceTester->getSystemSpaceTest($owners[3])) && p('name,k8space,owner,default') && e('系统空间,quickon-system,test,0');  // 获取用户名不存在的系统空间
