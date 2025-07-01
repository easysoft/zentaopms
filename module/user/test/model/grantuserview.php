#!/usr/bin/env php
<?php

/**

title=测试 userModel->grantUserView();
timeout=0
cid=0

- 获取admin账户最终可见的项目集列表属性programs @1,1,4,7,10
- 获取admin账户最终可见的产品列表属性products @1,1,2,3,4,5,6,7,8,9,10
- 获取admin账户最终可见的项目列表属性projects @11,13,16,19
- 获取admin账户最终可见的迭代列表属性sprints @101,101,103,109
- 获取user2账户最终可见的项目集列表属性programs @2,3,8,9,1,4,7,10
- 获取user2账户最终可见的产品列表属性products @12,13,16,17,18,1,2,3,4,5,6,7,8,9,10
- 获取user2账户最终可见的项目列表属性projects @12,14,17,18,13,16,19
- 获取user2账户最终可见的迭代列表属性sprints @107,108,113,114,102,103,104,109,102

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

zenData('user')->gen(10);
zenData('group')->loadYaml('group')->gen(100);
zenData('usergroup')->loadYaml('usergroup')->gen(100);
zenData('grouppriv')->loadYaml('grouppriv')->gen(100);
zenData('userview')->loadYaml('userview')->gen(10);
zenData('team')->loadYaml('team')->gen(200);
zenData('acl')->loadYaml('acl')->gen(100);
zenData('stakeholder')->loadYaml('stakeholder')->gen(100);

zenData('product')->gen(20);
zenData('project')->gen(0);
zenData('project')->gen(20);
zenData('project')->loadYaml('execution')->gen(20, false);
zenData('projectadmin')->loadYaml('projectadmin')->gen(30);

$user = new userTest();
r($user->grantUserViewTest('admin')) && p('programs', '-') && e('1,1,4,7,10');                          //获取admin账户最终可见的项目集列表
r($user->grantUserViewTest('admin')) && p('products', '-') && e('1,1,2,3,4,5,6,7,8,9,10');              //获取admin账户最终可见的产品列表
r($user->grantUserViewTest('admin')) && p('projects', '-') && e('11,13,16,19');                         //获取admin账户最终可见的项目列表
r($user->grantUserViewTest('admin')) && p('sprints' , '-') && e('101,101,103,109');                     //获取admin账户最终可见的迭代列表
r($user->grantUserViewTest('user2')) && p('programs', '-') && e('2,3,8,9,1,4,7,10');                    //获取user2账户最终可见的项目集列表
r($user->grantUserViewTest('user2')) && p('products', '-') && e('12,13,16,17,18,1,2,3,4,5,6,7,8,9,10'); //获取user2账户最终可见的产品列表
r($user->grantUserViewTest('user2')) && p('projects', '-') && e('12,14,17,18,13,16,19');                //获取user2账户最终可见的项目列表
r($user->grantUserViewTest('user2')) && p('sprints' , '-') && e('107,108,113,114,102,103,104,109,102'); //获取user2账户最终可见的迭代列表