#!/usr/bin/env php
<?php
/**

title=测试 spaceModel->getSpacesByAccount();
cid=1

- 获取用户名为空的空间列表 @0
- 获取用户名为admin的空间列表
 - 第0条的name属性 @空间1
 - 第0条的k8space属性 @k8space
 - 第0条的owner属性 @admin
 - 第0条的default属性 @0
- 获取用户名为user1的空间列表
 - 第0条的name属性 @空间2
 - 第0条的k8space属性 @k8space
 - 第0条的owner属性 @user1
 - 第0条的default属性 @0
- 获取用户名为test的空间列表 @0
- 获取用户名为空的空间数量 @0
- 获取用户名为admin的空间数量 @1
- 获取用户名为user1的空间数量 @1
- 获取用户名为test的空间数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/space.class.php';

zdTable('user')->gen(5);
zdTable('space')->config('space')->gen(5);

$owners = array('', 'admin', 'user1', 'test');

$spaceTester = new spaceTest();
r($spaceTester->getSpacesByAccountTest($owners[0])) && p()                               && e('0');                     // 获取用户名为空的空间列表
r($spaceTester->getSpacesByAccountTest($owners[1])) && p('0:name,k8space,owner,default') && e('空间1,k8space,admin,0'); // 获取用户名为admin的空间列表
r($spaceTester->getSpacesByAccountTest($owners[2])) && p('0:name,k8space,owner,default') && e('空间2,k8space,user1,0'); // 获取用户名为user1的空间列表
r($spaceTester->getSpacesByAccountTest($owners[3])) && p()                               && e('0');                     // 获取用户名为test的空间列表

r(count($spaceTester->getSpacesByAccountTest($owners[0]))) && p() && e('0'); // 获取用户名为空的空间数量
r(count($spaceTester->getSpacesByAccountTest($owners[1]))) && p() && e('1'); // 获取用户名为admin的空间数量
r(count($spaceTester->getSpacesByAccountTest($owners[2]))) && p() && e('1'); // 获取用户名为user1的空间数量
r(count($spaceTester->getSpacesByAccountTest($owners[3]))) && p() && e('0'); // 获取用户名为test的空间数量
