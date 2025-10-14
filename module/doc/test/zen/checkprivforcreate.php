#!/usr/bin/env php
<?php

/**

title=测试 docZen::checkPrivForCreate();
timeout=0
cid=0

- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclib1, 'custom'  @1
- 执行docTest2模块的checkPrivForCreateTest方法，参数是$doclib2, 'custom'  @0
- 执行docTest2模块的checkPrivForCreateTest方法，参数是$doclib3, 'custom'  @1
- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclib4, 'product'  @1
- 执行docTest模块的checkPrivForCreateTest方法，参数是$doclib5, 'project'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 准备产品数据
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->status->range('normal{5}');
$productTable->acl->range('open{5}');
$productTable->gen(5);

// 准备项目数据  
$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->status->range('wait{5}');
$projectTable->acl->range('open{5}');
$projectTable->type->range('project{5}');
$projectTable->gen(5);

// 准备执行数据
$executionTable = zenData('project');
$executionTable->id->range('6-10');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5');
$executionTable->status->range('wait{5}');
$executionTable->acl->range('open{5}');
$executionTable->type->range('stage{5}');
$executionTable->parent->range('1{5}');
$executionTable->gen(5);

// 准备用户组数据
$groupTable = zenData('group');
$groupTable->id->range('1-5');
$groupTable->name->range('管理员,开发,测试,产品,运维');
$groupTable->gen(5);

// 准备用户组关系数据
$userGroupTable = zenData('usergroup');
$userGroupTable->account->range('admin{1},user1{2},user2{2}');
$userGroupTable->group->range('1,2,3');
$userGroupTable->gen(5);

// 准备文档库数据
$table = zenData('doclib');
$table->id->range('1-20');
$table->type->range('custom{5},product{5},project{5},execution{5}');
$table->name->range('测试文档库1,测试文档库2,测试文档库3,测试文档库4,测试文档库5');
$table->acl->range('open{2},private{8},custom{10}');
$table->users->range('admin,user1{2},,{7},admin,user1,user2{7}');
$table->groups->range('1,2{4},,{15}');
$table->addedBy->range('admin{5},user1{5},user2{5},user3{5}');
$table->product->range('0{5},1{5},0{10}');
$table->project->range('0{10},1{5},0{5}');
$table->execution->range('0{15},6{5}');
$table->gen(20);

su('admin');

$docTest = new docTest();

// 测试步骤1：custom类型文档库，开放访问的库（所有用户都能访问）
$doclib1 = new stdClass();
$doclib1->acl = 'open';
$doclib1->users = 'user1,user2';
$doclib1->groups = '';
$doclib1->addedBy = 'user1';
r($docTest->checkPrivForCreateTest($doclib1, 'custom')) && p() && e('1');

// 测试步骤2：custom类型文档库，普通用户访问不在允许列表的私有库
$docTest2 = new docTest('user2');
$doclib2 = new stdClass();
$doclib2->acl = 'private';
$doclib2->users = 'user1';
$doclib2->groups = '';
$doclib2->addedBy = 'user1';
r($docTest2->checkPrivForCreateTest($doclib2, 'custom')) && p() && e('0');

// 测试步骤3：custom类型文档库，开放访问（所有用户都可以访问）
$doclib3 = new stdClass();
$doclib3->acl = 'open';
$doclib3->users = 'user1';
$doclib3->groups = '';
$doclib3->addedBy = 'user1';
r($docTest2->checkPrivForCreateTest($doclib3, 'custom')) && p() && e('1');

// 测试步骤4：product类型文档库，有产品权限的用户访问
su('admin');
$doclib4 = new stdClass();
$doclib4->product = 1;
$doclib4->acl = 'open';
r($docTest->checkPrivForCreateTest($doclib4, 'product')) && p() && e('1');

// 测试步骤5：project类型文档库，有项目权限的用户访问
$doclib5 = new stdClass();
$doclib5->project = 1;
$doclib5->acl = 'open';
r($docTest->checkPrivForCreateTest($doclib5, 'project')) && p() && e('1');