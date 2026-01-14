#!/usr/bin/env php
<?php

/**

title=测试 convertModel::importJiraData();
timeout=0
cid=15792

- 步骤1：默认参数调用，无会话数据返回finished结果属性finished @1
- 步骤2：创建表参数为true，返回finished结果属性finished @1
- 步骤3：指定type为user，返回finished结果属性finished @1
- 步骤4：指定lastID为100，返回finished结果属性finished @1
- 步骤5：多参数组合调用，返回finished结果属性finished @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 设置测试环境（模拟session数据）
global $app;
$app->session->jiraDB = 'test_jira_db';
$app->session->jiraMethod = 'file';
$app->session->jiraRelation = array();
$app->session->stepStatus = array();
$app->session->jiraUser = array();

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->importJiraDataTest()) && p('finished') && e('1'); // 步骤1：默认参数调用，无会话数据返回finished结果
r($convertTest->importJiraDataTest('', 0, true)) && p('finished') && e('1'); // 步骤2：创建表参数为true，返回finished结果
r($convertTest->importJiraDataTest('user', 0, false)) && p('finished') && e('1'); // 步骤3：指定type为user，返回finished结果
r($convertTest->importJiraDataTest('project', 100, false)) && p('finished') && e('1'); // 步骤4：指定lastID为100，返回finished结果
r($convertTest->importJiraDataTest('issue', 50, true)) && p('finished') && e('1'); // 步骤5：多参数组合调用，返回finished结果