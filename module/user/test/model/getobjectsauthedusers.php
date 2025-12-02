#!/usr/bin/env php
<?php

/**

title=测试 userModel::getObjectsAuthedUsers();
timeout=0
cid=19620

- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array 属性admin @admin
- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array 属性admin @admin
- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array 属性admin @admin
- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array 属性admin @admin
- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array  @0
- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array  @0
- 执行userTest模块的getObjectsAuthedUsersTest方法，参数是array 
 - 属性admin @admin
 - 属性user4 @user4

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备
$user = zenData('user');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户一,用户二,用户三,用户四');
$user->role->range('admin,dev,qa,pm,po');
$user->deleted->range('0{5}');
$user->gen(5);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{5},program{3},sprint{2}');
$project->acl->range('open{3},private{4},program{3}');
$project->path->range('`,1,`,`,2,`,`,3,`,`,1,2,`,`,1,3,`,`,2,4,`,`,3,5,`,`,1,2,6,`,`,1,3,7,`');
$project->deleted->range('0{10}');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{3},branch{2}');
$product->acl->range('open{2},private{3}');
$product->deleted->range('0{5}');
$product->gen(5);

$team = zenData('team');
$team->root->range('1-10');
$team->account->range('admin{2},user1{3},user2{2},user3{2},user4{1}');
$team->type->range('project{6},execution{4}');
$team->gen(10);

$acl = zenData('acl');
$acl->account->range('admin,user1,user2,user3');
$acl->objectType->range('project,product,program,sprint');
$acl->objectID->range('1-5');
$acl->type->range('whitelist{8},stakeholder{4}');
$acl->gen(12);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$userTest = new userTest();

// 5. 测试步骤
// 创建项目对象
$projectObject = new stdClass();
$projectObject->id = 1;
$projectObject->type = 'project';
$projectObject->acl = 'private';
$projectObject->openedBy = 'admin';
$projectObject->PM = 'admin';
$projectObject->PO = '';
$projectObject->QD = '';
$projectObject->RD = '';
$projectObject->createdBy = 'admin';
$projectObject->parent = 0;

$stakeholderGroup = array(1 => array('admin' => 'admin', 'user1' => 'user1'));
$teamsGroup = array(1 => array('admin' => 'admin', 'user2' => 'user2'));
$whiteListGroup = array(1 => array('user3' => 'user3'));
$adminsGroup = array(1 => array('admin' => 'admin'));

r($userTest->getObjectsAuthedUsersTest(array($projectObject), 'project', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup)) && p('admin') && e('admin');

// 创建产品对象
$productObject = new stdClass();
$productObject->id = 1;
$productObject->type = 'product';
$productObject->acl = 'private';
$productObject->PO = 'admin';
$productObject->QD = '';
$productObject->RD = '';
$productObject->createdBy = 'admin';

r($userTest->getObjectsAuthedUsersTest(array($productObject), 'product', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup)) && p('admin') && e('admin');

// 创建程序对象
$programObject = new stdClass();
$programObject->id = 1;
$programObject->type = 'program';
$programObject->acl = 'program';
$programObject->path = ',1,';
$programObject->openedBy = 'admin';
$programObject->PM = 'admin';
$programObject->parent = 0;

r($userTest->getObjectsAuthedUsersTest(array($programObject), 'program', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup)) && p('admin') && e('admin');

// 创建迭代对象
$sprintObject = new stdClass();
$sprintObject->id = 1;
$sprintObject->type = 'sprint';
$sprintObject->project = 1;
$sprintObject->openedBy = 'admin';
$sprintObject->PM = 'admin';
$sprintObject->PO = '';
$sprintObject->QD = '';
$sprintObject->RD = '';
$sprintObject->acl = 'private';
$sprintObject->parent = 0;

$parentTeamsGroup = array(1 => array('admin' => 'admin'));

r($userTest->getObjectsAuthedUsersTest(array($sprintObject), 'sprint', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup)) && p('admin') && e('admin');

// 测试空对象数组
r($userTest->getObjectsAuthedUsersTest(array(), 'project', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup)) && p() && e(0);

// 测试无效对象类型
r($userTest->getObjectsAuthedUsersTest(array($projectObject), 'invalid', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup)) && p() && e(0);

// 测试带父级权限的程序
$parentStakeholderGroup = array(1 => array('user4' => 'user4'));
$parentPMGroup = array(1 => array('admin' => 'admin'));

r($userTest->getObjectsAuthedUsersTest(array($programObject), 'program', $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup, $parentStakeholderGroup, $parentPMGroup)) && p('admin,user4') && e('admin,user4');