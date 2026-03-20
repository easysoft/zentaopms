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
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('team')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createTeamMemberTest(1, 'admin', 'project'))      && p() && e('1'); // 步骤1：正常创建project类型团队成员
r($convertTest->createTeamMemberTest(2, 'user1', 'execution'))    && p() && e('1'); // 步骤2：正常创建execution类型团队成员
r($convertTest->createTeamMemberTest(100, 'testuser', 'project')) && p() && e('1'); // 步骤3：测试大ID值
r($convertTest->createTeamMemberTest(5, 'user2', 'execution'))    && p() && e('1'); // 步骤4：测试不同账户名
r($convertTest->createTeamMemberTest(3, 'user3', 'project'))      && p() && e('1'); // 步骤5：再次验证正常创建
