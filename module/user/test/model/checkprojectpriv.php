#!/usr/bin/env php
<?php

/**

title=测试 userModel::checkProjectPriv();
timeout=0
cid=19591

- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @0
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1
- 执行userTest模块的checkProjectPrivTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{5},sprint{3},stage{1},kanban{1}');
$project->acl->range('open{5},private{3},program{2}');
$project->PO->range('po1,po2,,,,,,,,');
$project->PM->range('pm1,pm2,pm3,pm4,pm5,,,,,');
$project->QD->range('qd1,qd2,qd3,,,,,,');
$project->RD->range('rd1,rd2,rd3,rd4,,,,,');
$project->parent->range('0{7},1{2},2{1}');
$project->path->range(',1,,1,2,,1,3,,1,2,3,,,,,1,8,,1,2,9,');
$project->project->range('0{7},1{2},2{1}');
$project->openedBy->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,user10');
$project->gen(10);

$user = zenData('user');
$user->id->range('1-20');
$user->account->range('admin,po1,pm1,qd1,rd1,user1,user2,user3,user4,user5,user6,user7,user8,user9,user10,stakeholder1,team1,white1,admin1,normal1');
$user->realname->range('管理员,产品负责人1,项目经理1,测试负责人1,发布负责人1,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9,用户10,干系人1,团队成员1,白名单1,管理员1,普通用户1');
$user->role->range('admin,admin,admin,admin,admin,user{15}');
$user->deleted->range('0{20}');
$user->gen(20);

$company = zenData('company');
$company->id->range('1');
$company->admins->range(',admin,');
$company->gen(1);

su('admin');

// 设置公司管理员信息
global $tester;
$tester->app->company = new stdClass();
$tester->app->company->admins = ',admin,';

$userTest = new userTest();

r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'admin', array(), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => 'pm1', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'pm1', array(), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => 'po1', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'po1', array(), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => 'qd1', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'qd1', array(), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => 'rd1', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'rd1', array(), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'open', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'user1', array(), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'normal1', array(), array(), array(), array())) && p() && e('0');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'stakeholder1', array('stakeholder1' => 'stakeholder1'), array(), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'team1', array(), array('team1' => 'team1'), array(), array())) && p() && e('1');
r($userTest->checkProjectPrivTest((object)array('id' => 1, 'acl' => 'private', 'PO' => '', 'PM' => '', 'QD' => '', 'RD' => '', 'type' => 'project', 'parent' => 0, 'path' => ',1,'), 'white1', array(), array(), array('white1' => 'white1'), array())) && p() && e('1');