#!/usr/bin/env php
<?php

/**

title=测试 convertModel::processJiraUser();
timeout=0
cid=15794

- 步骤1：account模式正常用户名 @testuser
- 步骤2：长用户名截取 @verylongusernameexceedsthi
- 步骤3：email模式邮箱处理 @admin
- 步骤4：email模式无符号 @testuser
- 步骤5：特殊字符清理 @testuser123name

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->processJiraUserTest('testuser', 'test@example.com', array('mode' => 'account'))) && p() && e('testuser'); // 步骤1：account模式正常用户名
r($convertTest->processJiraUserTest('very_long_username_exceeds_thirty_character_limit_test', 'test@example.com', array('mode' => 'account'))) && p() && e('verylongusernameexceedsthi'); // 步骤2：长用户名截取
r($convertTest->processJiraUserTest('jirauser', 'admin@example.com', array('mode' => 'email'))) && p() && e('admin'); // 步骤3：email模式邮箱处理
r($convertTest->processJiraUserTest('jirauser', 'testuser', array('mode' => 'email'))) && p() && e('testuser'); // 步骤4：email模式无符号
r($convertTest->processJiraUserTest('test-user_123.name', '', array('mode' => 'account'))) && p() && e('testuser123name'); // 步骤5：特殊字符清理