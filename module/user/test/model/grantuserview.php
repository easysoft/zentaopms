#!/usr/bin/env php
<?php

/**

title=测试 userModel->grantUserView();
cid=0

- 获取admin账户最终可见的项目集列表属性programs @2,3,5,6,8,9,1,4,7,10
- 获取admin账户最终可见的产品列表属性products @11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9,10
- 获取admin账户最终可见的项目列表属性projects @11,12,14,15,17,18,20,13,16,19
- 获取admin账户最终可见的迭代列表属性sprints @105,106,107,108,113,114,115,116,101,102,103,104,109,110
- 获取user2账户最终可见的项目集列表属性programs @2,3,8,9,1,4,7,10
- 获取user2账户最终可见的产品列表属性products @12,13,17,18,1,2,3,4,5,6,7,8,9,10
- 获取user2账户最终可见的项目列表属性projects @12,14,17,18,13,16,19
- 获取user2账户最终可见的迭代列表属性sprints @107,108,113,114,102,103,104,109

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

dao::$cache = array();
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
r($user->grantUserViewTest('admin')) && p('programs', '-') && e('2,3,5,6,8,9,1,4,7,10');                                    //获取admin账户最终可见的项目集列表
r($user->grantUserViewTest('admin')) && p('products', '-') && e('11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9,10');      //获取admin账户最终可见的产品列表
r($user->grantUserViewTest('admin')) && p('projects', '-') && e('11,12,14,15,17,18,20,13,16,19');                           //获取admin账户最终可见的项目列表
r($user->grantUserViewTest('admin')) && p('sprints' , '-') && e('105,106,107,108,113,114,115,116,101,102,103,104,109,110'); //获取admin账户最终可见的迭代列表
r($user->grantUserViewTest('user2')) && p('programs', '-') && e('2,3,8,9,1,4,7,10');                                        //获取user2账户最终可见的项目集列表
r($user->grantUserViewTest('user2')) && p('products', '-') && e('12,13,17,18,1,2,3,4,5,6,7,8,9,10');                        //获取user2账户最终可见的产品列表
r($user->grantUserViewTest('user2')) && p('projects', '-') && e('12,14,17,18,13,16,19');                                    //获取user2账户最终可见的项目列表
r($user->grantUserViewTest('user2')) && p('sprints' , '-') && e('107,108,113,114,102,103,104,109');                         //获取user2账户最终可见的迭代列表
