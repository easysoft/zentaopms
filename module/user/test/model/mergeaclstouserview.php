#!/usr/bin/env php
<?php

/**

title=测试 userModel::mergeAclsToUserView();
timeout=0
cid=0

- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'admin', $adminView, $acls, ''
 - 属性programs @3
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user1', $userView1, $acls, ''
 - 属性programs @3
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user2', $userView1, $acls, '5, 6'
 - 属性projects @3
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'user3', $userView2, $emptyAcls, ''
 - 属性programs @7
- 执行userTest模块的mergeAclsToUserViewTest方法，参数是'manager', $userView1, $acls, ''
 - 属性sprints @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

$company = zendata('company');
$company->id->range('1');
$company->name->range('测试公司');
$company->admins->range(',admin,');
$company->gen(1);

$project = zendata('project');
$project->id->range('1-20');
$project->name->range('项目1,项目2,项目3,迭代1,迭代2,迭代3');
$project->type->range('project{10},sprint{5},stage{3},kanban{2}');
$project->status->range('open{15},closed{3},suspended{2}');
$project->project->range('1{5},2{3},3{2},0{10}');
$project->acl->range('open{10},private{5},custom{5}');
$project->gen(20);

$projectadmin = zendata('projectadmin');
$projectadmin->group->range('1-5');
$projectadmin->account->range('admin,manager,user1');
$projectadmin->programs->range('1,2,3');
$projectadmin->projects->range('1,2,3');
$projectadmin->products->range('1,2,3');
$projectadmin->executions->range('1,2,3,all');
$projectadmin->gen(3);

su('admin');

$userTest = new userTest();

// 准备测试数据
$adminView = new stdClass();
$adminView->programs = '1,2';
$adminView->products = '1,2';
$adminView->projects = '1,2';
$adminView->sprints = '1,2';

$userView1 = new stdClass();
$userView1->programs = '1,2';
$userView1->products = '1,2';
$userView1->projects = '1,2';
$userView1->sprints = '1,2';

$userView2 = new stdClass();
$userView2->programs = '7,8';
$userView2->products = '7,8';
$userView2->projects = '7,8';
$userView2->sprints = '7,8';

$acls = array(
    'programs' => array(3, 4),
    'products' => array(3, 4),
    'projects' => array(3, 4),
    'sprints' => array(3, 4)
);

$emptyAcls = array();

su('admin');
r($userTest->mergeAclsToUserViewTest('admin', $adminView, $acls, '')) && p('programs') && e('3,4');
su('user');
r($userTest->mergeAclsToUserViewTest('user1', $userView1, $acls, '')) && p('programs') && e('3,4');
r($userTest->mergeAclsToUserViewTest('user2', $userView1, $acls, '5,6')) && p('projects') && e('3,4,5,6');
r($userTest->mergeAclsToUserViewTest('user3', $userView2, $emptyAcls, '')) && p('programs') && e('7,8');
r($userTest->mergeAclsToUserViewTest('manager', $userView1, $acls, '')) && p('sprints') && e('3,4,,2');