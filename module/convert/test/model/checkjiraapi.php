#!/usr/bin/env php
<?php

/**

title=测试 convertModel::checkJiraApi();
timeout=0
cid=15764

- 步骤1：session无jiraApi数据时返回false @0
- 步骤2：domain为空时返回false @0
- 步骤3：无效domain时返回false @0
- 步骤4：错误认证时返回false @0
- 步骤5：有效配置时返回false（测试环境无法连接） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->checkJiraApiTest()) && p() && e('0'); // 步骤1：session无jiraApi数据时返回false
r($convertTest->checkJiraApiTest(array('admin' => 'test', 'token' => 'token'))) && p() && e('0'); // 步骤2：domain为空时返回false
r($convertTest->checkJiraApiTest(array('domain' => 'http://invalid-domain.com', 'admin' => 'test', 'token' => 'token'))) && p() && e('0'); // 步骤3：无效domain时返回false
r($convertTest->checkJiraApiTest(array('domain' => 'https://test.atlassian.net', 'admin' => 'wronguser', 'token' => 'wrongtoken'))) && p() && e('0'); // 步骤4：错误认证时返回false
r($convertTest->checkJiraApiTest(array('domain' => 'https://test.atlassian.net', 'admin' => 'admin', 'token' => 'validtoken'))) && p() && e('0'); // 步骤5：有效配置时返回false（测试环境无法连接）