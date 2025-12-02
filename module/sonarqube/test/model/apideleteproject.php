#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiDeleteProject();
timeout=0
cid=18374

- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是$invalidSonarqubeID, $emptyProjectKey  @return false
- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是$validSonarqubeID, $emptyProjectKey  @return true
- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是$validSonarqubeID, $nonExistentProjectKey  @return true
- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是$validSonarqubeID, $validProjectKey  @return true
- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是$negativeSonarqubeID, $validProjectKey  @return false
- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是$validSonarqubeID, $specialCharProjectKey  @return true
- 执行sonarqubeTest模块的apiDeleteProjectTest方法，参数是0, $validProjectKey  @return false

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

// 准备测试数据
$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('sonarqube{5},gitlab{3},jenkins{2}');
$table->name->range('SonarQube测试服务器{5},GitLab服务器{3},Jenkins服务器{2}');
$table->url->range('https://test.sonarqube.com{5},https://gitlab.com{3},https://jenkins.com{2}');
$table->account->range('admin{10}');
$table->token->range('dGVzdF90b2tlbl8xMjM={10}');
$table->deleted->range('0');
$table->gen(10);

// 设置测试用户权限
su('admin');

// 测试参数定义
$validSonarqubeID = 1;
$invalidSonarqubeID = 999;
$negativeSonarqubeID = -1;
$emptyProjectKey = '';
$nonExistentProjectKey = 'non_existent_project_123';
$validProjectKey = 'test_delete_project_123';
$specialCharProjectKey = 'test@#$%^&*()_project';

// 创建测试实例
$sonarqubeTest = new sonarqubeTest();

// 测试步骤1：使用无效的sonarqubeID删除项目
r($sonarqubeTest->apiDeleteProjectTest($invalidSonarqubeID, $emptyProjectKey)) && p() && e('return false');

// 测试步骤2：使用有效sonarqubeID但空projectKey删除项目
r($sonarqubeTest->apiDeleteProjectTest($validSonarqubeID, $emptyProjectKey)) && p() && e('return true');

// 测试步骤3：使用有效sonarqubeID但不存在的projectKey
r($sonarqubeTest->apiDeleteProjectTest($validSonarqubeID, $nonExistentProjectKey)) && p() && e('return true');

// 测试步骤4：使用有效参数删除项目
r($sonarqubeTest->apiDeleteProjectTest($validSonarqubeID, $validProjectKey)) && p() && e('return true');

// 测试步骤5：测试负数sonarqubeID的处理
r($sonarqubeTest->apiDeleteProjectTest($negativeSonarqubeID, $validProjectKey)) && p() && e('return false');

// 测试步骤6：使用特殊字符的projectKey删除项目
r($sonarqubeTest->apiDeleteProjectTest($validSonarqubeID, $specialCharProjectKey)) && p() && e('return true');

// 测试步骤7：测试边界值 - 零值ID
r($sonarqubeTest->apiDeleteProjectTest(0, $validProjectKey)) && p() && e('return false');