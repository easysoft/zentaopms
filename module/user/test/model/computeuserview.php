#!/usr/bin/env php
<?php

/**

title=测试 userModel->computeUserView();
timeout=0
cid=19595

- 不强制更新，获取test1账户的可查看项目集列表。属性programs @,3
- 不强制更新，获取test1账户的可查看产品列表。属性products @,3
- 不强制更新，获取test1账户的可查看项目列表。属性projects @,13
- 不强制更新，获取test1账户的可查看执行列表。属性sprints @,103
- 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看项目集列表。属性programs @~~
- 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看产品列表。属性products @~~
- 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看项目列表。属性projects @~~
- 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看执行列表。属性sprints @~~
- 强制更新，获取admin账户的可查看项目集列表。属性programs @2,3,5,6,8,9
- 强制更新，获取admin账户的可查看产品列表。属性products @11,12,13,14,15,16,17,18,19,20
- 强制更新，获取admin账户的可查看项目列表。属性projects @11,12,14,15,17,18,20
- 强制更新，获取admin账户的可查看执行列表。属性sprints @105,106,107,108,113,114,115,116
- 强制更新，获取user2账户的可查看项目集列表。属性programs @2,3,8,9
- 强制更新，获取user2账户的可查看产品列表。属性products @12,13,16,17,18
- 强制更新，获取user2账户的可查看项目列表。属性projects @12,14,17,18
- 强制更新，获取user2账户的可查看执行列表。属性sprints @107,108,113,114

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('userview')->loadYaml('userview')->gen(10);
zenData('team')->loadYaml('team')->gen(200);
zenData('acl')->loadYaml('acl')->gen(100);
zenData('stakeholder')->loadYaml('stakeholder')->gen(100);

zenData('product')->gen(20);
zenData('project')->gen(0);
zenData('project')->gen(20);
zenData('project')->loadYaml('execution')->gen(20, false);
zenData('projectadmin')->loadYaml('projectadmin')->gen(30);

$time = time();

global $config;
$config->userview->updateTime              = $time + 1;
$config->userview->relatedTablesUpdateTime = $time;

$user = new userModelTest();
r($user->computeUserViewTest('test1', false)) && p('programs', '-') && e(',3');   // 不强制更新，获取test1账户的可查看项目集列表。
r($user->computeUserViewTest('test1', false)) && p('products', '-') && e(',3');   // 不强制更新，获取test1账户的可查看产品列表。
r($user->computeUserViewTest('test1', false)) && p('projects', '-') && e(',13');  // 不强制更新，获取test1账户的可查看项目列表。
r($user->computeUserViewTest('test1', false)) && p('sprints',  '-') && e(',103'); // 不强制更新，获取test1账户的可查看执行列表。

$config->userview->updateTime = $time - 1;
r($user->computeUserViewTest('test1', false)) && p('programs', '-') && e('~~'); // 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看项目集列表。
r($user->computeUserViewTest('test1', false)) && p('products', '-') && e('~~'); // 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看产品列表。
r($user->computeUserViewTest('test1', false)) && p('projects', '-') && e('~~'); // 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看项目列表。
r($user->computeUserViewTest('test1', false)) && p('sprints',  '-') && e('~~'); // 不强制更新，用户视图最后更新时间小于相关表最后更新时间，获取test1账户的可查看执行列表。

r($user->computeUserViewTest('admin', true)) && p('programs', '-') && e('2,3,5,6,8,9');                     //强制更新，获取admin账户的可查看项目集列表。
r($user->computeUserViewTest('admin', true)) && p('products', '-') && e('11,12,13,14,15,16,17,18,19,20');   //强制更新，获取admin账户的可查看产品列表。
r($user->computeUserViewTest('admin', true)) && p('projects', '-') && e('11,12,14,15,17,18,20');            //强制更新，获取admin账户的可查看项目列表。
r($user->computeUserViewTest('admin', true)) && p('sprints',  '-') && e('105,106,107,108,113,114,115,116'); //强制更新，获取admin账户的可查看执行列表。

r($user->computeUserViewTest('user2', true)) && p('programs', '-') && e('2,3,8,9');                         //强制更新，获取user2账户的可查看项目集列表。
r($user->computeUserViewTest('user2', true)) && p('products', '-') && e('12,13,16,17,18');                  //强制更新，获取user2账户的可查看产品列表。
r($user->computeUserViewTest('user2', true)) && p('projects', '-') && e('12,14,17,18');                     //强制更新，获取user2账户的可查看项目列表。
r($user->computeUserViewTest('user2', true)) && p('sprints',  '-') && e('107,108,113,114');                 //强制更新，获取user2账户的可查看执行列表。