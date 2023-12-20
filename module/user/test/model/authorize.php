#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

su('admin');

zdTable('user')->gen(20);
zdTable('group')->config('group')->gen(100);
zdTable('usergroup')->config('usergroup')->gen(100);
zdTable('grouppriv')->config('grouppriv')->gen(100);

/**

title=测试 userModel->authorize();
cid=1
pid=1

- 获取user2用户的访问权限。
 - 第rights[index]条的index属性 @1
 - 第rights[module3]条的method3属性 @1
- 获取user2用户的可访问项目集、项目、产品。
 - 第acls[programs]条的0属性 @1
 - 第acls[programs]条的1属性 @6
- 获取user2用户的可访问项目集、项目、产品。第acls[projects]条的0属性 @15
- 获取user2用户的可访问项目集、项目、产品。第acls[products]条的0属性 @20
- 获取user2用户的可访问视图。
 - 第acls[views]条的program属性 @program
 - 第acls[views]条的product属性 @product
 - 第acls[views]条的project属性 @project

*/

$user = new userTest();

r($user->authorizeTest('user2')) && p('rights[index]:index;rights[module3]:method3') && e('1,1');                     // 获取user2用户的访问权限。
r($user->authorizeTest('user2')) && p('acls[programs]:0,1')                          && e('1,6');                     // 获取user2用户的可访问项目集、项目、产品。
r($user->authorizeTest('user2')) && p('acls[projects]:0')                            && e('15');                      // 获取user2用户的可访问项目集、项目、产品。
r($user->authorizeTest('user2')) && p('acls[products]:0')                            && e('20');                      // 获取user2用户的可访问项目集、项目、产品。
r($user->authorizeTest('user2')) && p('acls[views]:program,product,project')         && e('program,product,project'); // 获取user2用户的可访问视图。
