#!/usr/bin/env php
<?php
/**

title=测试 userModel->grantUserView();
cid=1
pid=1

- 获取admin账户最终可见的项目集列表属性programs @1,1,4,7,10
- 获取admin账户最终可见的产品列表属性products @1,1,2,3,4,5,6,7,8,9,10
- 获取admin账户最终可见的项目列表属性projects @11,13,16,19
- 获取admin账户最终可见的迭代列表属性sprints @101,101,103,109
- 获取user2账户最终可见的项目集列表属性programs @4,1,4,7,10
- 获取user2账户最终可见的产品列表属性products @4,1,2,3,4,5,6,7,8,9,10
- 获取user2账户最终可见的项目列表属性projects @14,13,16,19
- 获取user2账户最终可见的迭代列表属性sprints @104,103,104,109

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

dao::$cache = array();
zdTable('user')->gen(20);
zdTable('usergroup')->gen(100);
zdTable('userview')->config('userview')->gen(10);
zdTable('team')->config('team')->gen(200);
zdTable('acl')->config('acl')->gen(100);
zdTable('stakeholder')->config('stakeholder')->gen(100);

zdTable('product')->gen(20);
zdTable('project')->gen(0);
zdTable('project')->gen(20);
zdTable('project')->config('execution')->gen(20, false);
zdTable('projectadmin')->config('projectadmin')->gen(30);

$user = new userTest();
r($user->grantUserViewTest('admin')) && p('programs', '-') && e('1,1,4,7,10');             //获取admin账户最终可见的项目集列表
r($user->grantUserViewTest('admin')) && p('products', '-') && e('1,1,2,3,4,5,6,7,8,9,10'); //获取admin账户最终可见的产品列表
r($user->grantUserViewTest('admin')) && p('projects', '-') && e('11,13,16,19');            //获取admin账户最终可见的项目列表
r($user->grantUserViewTest('admin')) && p('sprints' , '-') && e('101,101,103,109');        //获取admin账户最终可见的迭代列表
r($user->grantUserViewTest('user2')) && p('programs', '-') && e('4,1,4,7,10');             //获取user2账户最终可见的项目集列表
r($user->grantUserViewTest('user2')) && p('products', '-') && e('4,1,2,3,4,5,6,7,8,9,10'); //获取user2账户最终可见的产品列表
r($user->grantUserViewTest('user2')) && p('projects', '-') && e('14,13,16,19');            //获取user2账户最终可见的项目列表
r($user->grantUserViewTest('user2')) && p('sprints' , '-') && e('104,103,104,109');        //获取user2账户最终可见的迭代列表
