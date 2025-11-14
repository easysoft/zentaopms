#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createTeamMember();
timeout=0
cid=15847

- 步骤1：正常创建project类型团队成员 @1
- 步骤2：正常创建execution类型团队成员 @1
- 步骤3：测试大ID值 @1
- 步骤4：测试不同账户名 @1
- 步骤5：再次验证正常创建 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('team');
$table->id->range('1-100');
$table->root->range('1-10');
$table->type->range('project{5}, execution{5}');
$table->account->range('admin, user1, user2, user3, testuser');
$table->role->range('[]');
$table->position->range('[]');
$table->limited->range('no');
$table->join->range('2024-01-01:2024-12-31');
$table->days->range('0');
$table->hours->range('8.0');
$table->estimate->range('0.00');
$table->consumed->range('0.00');
$table->left->range('0.00');
$table->order->range('0');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createTeamMemberTest(1, 'admin', 'project')) && p() && e('1'); // 步骤1：正常创建project类型团队成员
r($convertTest->createTeamMemberTest(2, 'user1', 'execution')) && p() && e('1'); // 步骤2：正常创建execution类型团队成员
r($convertTest->createTeamMemberTest(100, 'testuser', 'project')) && p() && e('1'); // 步骤3：测试大ID值
r($convertTest->createTeamMemberTest(5, 'user2', 'execution')) && p() && e('1'); // 步骤4：测试不同账户名
r($convertTest->createTeamMemberTest(3, 'user3', 'project')) && p() && e('1'); // 步骤5：再次验证正常创建