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
zdTable('project')->gen(0);
zdTable('project')->gen(20);
zdTable('project')->config('execution')->gen(20, false);
zdTable('projectadmin')->config('projectadmin')->gen(30);

$user = new userTest();
r($user->grantUserViewTest('admin')) && p('programs', '-') && e('2,3,5,6,8,9,1,4,7,10');                                    //获取admin账户最终可见的项目集列表
r($user->grantUserViewTest('admin')) && p('products', '-') && e('11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9,10');      //获取admin账户最终可见的产品列表
r($user->grantUserViewTest('admin')) && p('projects', '-') && e('11,12,14,15,17,18,20,13,16,19');                           //获取admin账户最终可见的项目列表
r($user->grantUserViewTest('admin')) && p('sprints' , '-') && e('105,106,107,108,113,114,115,116,101,102,103,104,109,110'); //获取admin账户最终可见的迭代列表
r($user->grantUserViewTest('user2')) && p('programs', '-') && e('2,3,8,9,1,4,7,10');                                        //获取user2账户最终可见的项目集列表
r($user->grantUserViewTest('user2')) && p('products', '-') && e('12,13,17,18,1,2,3,4,5,6,7,8,9,10');                        //获取user2账户最终可见的产品列表
r($user->grantUserViewTest('user2')) && p('projects', '-') && e('12,14,17,18,13,16,19');                                    //获取user2账户最终可见的项目列表
r($user->grantUserViewTest('user2')) && p('sprints' , '-') && e('107,108,113,114,102,103,104,109');                         //获取user2账户最终可见的迭代列表
