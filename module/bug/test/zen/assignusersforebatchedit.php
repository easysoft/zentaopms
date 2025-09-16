#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignUsersForBatchEdit();
timeout=0
cid=0

- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'normal', 'product'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'normal', 'project'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'normal', 'execution'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'empty', 'project'  @10
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'branch', 'execution'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'single_project', 'project'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'multi_branch', 'project'  @1
- 执行bugTest模块的assignUsersForBatchEditTest方法，参数是'no_execution', 'execution'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// zendata数据准备
$user = zenData('user');
$user->account->range('admin,user1,user2,user3,user4,user5,dev1,dev2,qa1,qa2');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,开发1,开发2,测试1,测试2');
$user->type->range('inside{10}');
$user->deleted->range('0{10}');
$user->gen(10);

$product = zenData('product');
$product->id->range('1-4');
$product->name->range('产品1,产品2,产品3,产品4');
$product->type->range('normal,branch,normal,branch');
$product->deleted->range('0{4}');
$product->gen(4);

$project = zenData('project');
$project->id->range('1,2,11,12');
$project->name->range('项目1,项目2,项目集1,项目集2');
$project->type->range('project{2},program{2}');
$project->model->range('scrum{2},kanban{2}');
$project->parent->range('0{2},0{2}');
$project->multiple->range('1,0,1,0');
$project->deleted->range('0{4}');
$project->gen(4);

$execution = zenData('project', 'execution');
$execution->id->range('101,102,103');
$execution->project->range('1,2,1');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('sprint,kanban,sprint');
$execution->parent->range('1,2,1');
$execution->deleted->range('0{3}');
$execution->gen(3);

$team = zenData('team');
$team->root->range('1,1,2,2,101,101,102,102,103,103');
$team->type->range('project{4},execution{6}');
$team->account->range('admin,dev1,user1,dev2,admin,qa1,user2,qa2,dev1,qa1');
$team->gen(10);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('2,2,2,4,4');
$branch->name->range('分支1,分支2,分支3,分支4,分支5');
$branch->deleted->range('0{5}');
$branch->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$bugTest = new bugTest();

// 测试步骤1：产品标签页下调用方法 - 不设置团队成员信息返回1
r($bugTest->assignUsersForBatchEditTest('normal', 'product')) && p() && e('1');

// 测试步骤2：项目标签页下正常bugs数据 - 设置团队成员信息返回1
r($bugTest->assignUsersForBatchEditTest('normal', 'project')) && p() && e('1');

// 测试步骤3：执行标签页下正常bugs数据 - 设置团队成员信息返回1
r($bugTest->assignUsersForBatchEditTest('normal', 'execution')) && p() && e('1');

// 测试步骤4：空bugs数据在项目标签页下 - 返回用户数量10
r($bugTest->assignUsersForBatchEditTest('empty', 'project')) && p() && e('10');

// 测试步骤5：带分支产品的bugs数据在执行标签页下 - 设置分支团队成员返回1
r($bugTest->assignUsersForBatchEditTest('branch', 'execution')) && p() && e('1');

// 测试步骤6：单项目模式下批量编辑 - 隐藏计划字段返回1
r($bugTest->assignUsersForBatchEditTest('single_project', 'project')) && p() && e('1');

// 测试步骤7：多分支产品在项目标签页下 - 设置多分支团队成员返回1
r($bugTest->assignUsersForBatchEditTest('multi_branch', 'project')) && p() && e('1');

// 测试步骤8：无执行的bugs数据在执行标签页下 - 仅设置项目成员返回1
r($bugTest->assignUsersForBatchEditTest('no_execution', 'execution')) && p() && e('1');