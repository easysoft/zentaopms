#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignUsersForBatchEdit();
timeout=0
cid=0

- 步骤1：产品页面正常情况 @1
- 步骤2：项目页面 @1
- 步骤3：执行页面 @1
- 步骤4：空bug数组 @5
- 步骤5：分支产品情况 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-3');
$bug->project->range('1-2');
$bug->execution->range('1-2');
$bug->title->range('Bug title 1, Bug title 2, Bug title 3');
$bug->status->range('active');
$bug->assignedTo->range('admin,user1,user2');
$bug->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('Product 1, Product 2, Product 3');
$product->type->range('normal{2},branch{1}');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-2');
$project->name->range('Project 1, Project 2');
$project->type->range('project');
$project->multiple->range('1');
$project->gen(2);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('Admin,User 1,User 2,User 3,User 4');
$user->deleted->range('0');
$user->gen(5);

$team = zenData('team');
$team->id->range('1-8');
$team->root->range('1{4},2{4}');
$team->type->range('project');
$team->account->range('admin,user1,user2,user3');
$team->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->assignUsersForBatchEditTest('normal', 'product')) && p() && e(1); // 步骤1：产品页面正常情况
r($bugTest->assignUsersForBatchEditTest('normal', 'project')) && p() && e(1); // 步骤2：项目页面
r($bugTest->assignUsersForBatchEditTest('normal', 'execution')) && p() && e(1); // 步骤3：执行页面
r($bugTest->assignUsersForBatchEditTest('empty', 'product')) && p() && e(5); // 步骤4：空bug数组
r($bugTest->assignUsersForBatchEditTest('branch', 'project')) && p() && e(1); // 步骤5：分支产品情况