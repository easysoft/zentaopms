#!/usr/bin/env php
<?php
/**

title=测试 userModel->computeUserView();
cid=1
pid=1

- 计算并获取test1账户的可查看项目集列表。属性programs @,1
- 计算并获取test1账户的可查看产品列表。属性products @,1
- 计算并获取test1账户的可查看项目列表。属性projects @,11
- 计算并获取test1账户的可查看执行列表。属性sprints @,101
- 计算并获取admin账户的可查看项目集列表。属性programs @2,3,5,6,8,9
- 计算并获取admin账户的可查看产品列表。属性products @11,12,13,14,15,16,17,18,19,20
- 计算并获取admin账户的可查看项目列表。属性projects @11,12,14,15,17,18,20
- 计算并获取admin账户的可查看执行列表。属性sprints @105,106,107,108,113,114,115,116
- 计算并获取user2账户的可查看项目集列表。属性programs @2,3,8,9
- 计算并获取user2账户的可查看产品列表。属性products @12,13,17,18
- 计算并获取user2账户的可查看项目列表。属性projects @12,14,17,18
- 计算并获取user2账户的可查看执行列表。属性sprints @107,108,113,114

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(10);
zdTable('userview')->gen(10);
zdTable('team')->config('team')->gen(200);
zdTable('acl')->config('acl')->gen(100);
zdTable('stakeholder')->config('stakeholder')->gen(100);

zdTable('product')->gen(20);
zdTable('project')->gen(0);
zdTable('project')->gen(20);
zdTable('project')->config('execution')->gen(20, false);
zdTable('projectadmin')->config('projectadmin')->gen(30);

$user = new userTest();
r($user->computeUserViewTest('test1', false))  && p('programs', '-') && e(',1');                              //计算并获取test1账户的可查看项目集列表。
r($user->computeUserViewTest('test1', false))  && p('products', '-') && e(',1');                              //计算并获取test1账户的可查看产品列表。
r($user->computeUserViewTest('test1', false))  && p('projects', '-') && e(',11');                             //计算并获取test1账户的可查看项目列表。
r($user->computeUserViewTest('test1', false))  && p('sprints',  '-') && e(',101');                            //计算并获取test1账户的可查看执行列表。
r($user->computeUserViewTest('admin', true))   && p('programs', '-') && e('2,3,5,6,8,9');                     //计算并获取admin账户的可查看项目集列表。
r($user->computeUserViewTest('admin', true))   && p('products', '-') && e('11,12,13,14,15,16,17,18,19,20');   //计算并获取admin账户的可查看产品列表。
r($user->computeUserViewTest('admin', true))   && p('projects', '-') && e('11,12,14,15,17,18,20');            //计算并获取admin账户的可查看项目列表。
r($user->computeUserViewTest('admin', true))   && p('sprints',  '-') && e('105,106,107,108,113,114,115,116'); //计算并获取admin账户的可查看执行列表。
r($user->computeUserViewTest('user2', true))   && p('programs', '-') && e('2,3,8,9');                         //计算并获取user2账户的可查看项目集列表。
r($user->computeUserViewTest('user2', true))   && p('products', '-') && e('12,13,17,18');                     //计算并获取user2账户的可查看产品列表。
r($user->computeUserViewTest('user2', true))   && p('projects', '-') && e('12,14,17,18');                     //计算并获取user2账户的可查看项目列表。
r($user->computeUserViewTest('user2', true))   && p('sprints',  '-') && e('107,108,113,114');                 //计算并获取user2账户的可查看执行列表。
