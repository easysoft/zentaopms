#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraAccount();
timeout=0
cid=15772

- 步骤1：空userKey输入 @0
- 步骤2：有效JIRAUSER前缀ID @jirauser
- 步骤3：不存在的JIRAUSER前缀ID @0
- 步骤4：直接用户账号名 @testuser
- 步骤5：不存在的用户账号 @nonexistuser

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('1-5');
$table->account->range('admin,testuser,jirauser,normaluser,userwithsymbols');
$table->realname->range('Administrator,Test User,Jira User,Normal User,User With Symbols');
$table->email->range('admin@test.com,testuser@example.com,jirauser@company.org,normal@domain.net,user.symbols@email.com');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($convertTest->getJiraAccountTest('')) && p() && e('0'); // 步骤1：空userKey输入
r($convertTest->getJiraAccountTest('JIRAUSER3')) && p() && e('jirauser'); // 步骤2：有效JIRAUSER前缀ID
r($convertTest->getJiraAccountTest('JIRAUSER999')) && p() && e('0'); // 步骤3：不存在的JIRAUSER前缀ID
r($convertTest->getJiraAccountTest('testuser')) && p() && e('testuser'); // 步骤4：直接用户账号名
r($convertTest->getJiraAccountTest('nonexistuser')) && p() && e('nonexistuser'); // 步骤5：不存在的用户账号