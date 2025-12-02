#!/usr/bin/env php
<?php

/**

title=测试 taskZen::isLimitedInExecution();
timeout=0
cid=18935

- 步骤1：管理员用户不受限 @0
- 步骤2：管理员用户不受限 @0
- 步骤3：普通用户在受限执行中受限 @1
- 步骤4：不存在的执行ID不受限 @0
- 步骤5：边界值执行ID为0不受限 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$team = zenData('team');
$team->id->range('1-15');
$team->root->range('1,2,3,4,5{2},101,102,103,104,105{2}');
$team->type->range('project{5},execution{10}');
$team->account->range('admin{3},user1{4},user2{4},user3{4}');
$team->role->range('admin,dev,qa,pm');
$team->limited->range('yes{8},no{7}');
$team->gen(15);

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0{5},1,1,2,2,3');
$project->type->range('project{5},sprint{5}');
$project->parent->range('0{5},1,1,2,2,3');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$project->status->range('wait{3},doing{4},suspended{2},closed{1}');
$project->openedBy->range('admin');
$project->openedDate->range('`2023-01-01 09:00:00`');
$project->gen(10);

$user = zenData('user');
$user->id->range('1-4');
$user->account->range('admin,user1,user2,user3');
$user->password->range('123456');
$user->realname->range('管理员,用户1,用户2,用户3');
$user->role->range('top,dev,qa,po');
$user->gen(4);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->isLimitedInExecutionTest(1)) && p() && e('0');            // 步骤1：管理员用户不受限
r($taskZenTest->isLimitedInExecutionTest(5)) && p() && e('0');            // 步骤2：管理员用户不受限

su('user1');
r($taskZenTest->isLimitedInExecutionTest(5)) && p() && e('1');            // 步骤3：普通用户在受限执行中受限
r($taskZenTest->isLimitedInExecutionTest(999)) && p() && e('0');          // 步骤4：不存在的执行ID不受限
r($taskZenTest->isLimitedInExecutionTest(0)) && p() && e('0');            // 步骤5：边界值执行ID为0不受限