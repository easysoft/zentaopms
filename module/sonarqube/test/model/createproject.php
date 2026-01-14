#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::createProject();
timeout=0
cid=18382

- 步骤1：正常数据创建项目 @1
- 步骤2：无效sonarqubeID创建项目 @return false
- 步骤3：项目键格式错误创建项目 @return error
- 步骤4：项目名称长度超限创建项目 @return false
- 步骤5：项目键长度超限创建项目第projectKey条的0属性 @『项目标识』长度应当不超过『400』
- 步骤6：空项目名称创建项目第name条的0属性 @The 'name' parameter is missing
- 步骤7：空项目键创建项目第name条的0属性 @The 'project' parameter is missing

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('pipeline')->loadYaml('pipeline')->gen(5);

$sonarqubeID = 2;

// 测试数据准备
$validProject = array('projectName' => 'unit_test_project', 'projectKey' => 'unittest_project');
$invalidKeyProject = array('projectName' => 'unit_test', 'projectKey' => '@#$invalid');
$longNameProject = array('projectName' => str_repeat('a', 256), 'projectKey' => 'valid_key');
$longKeyProject = array('projectName' => 'valid_name', 'projectKey' => str_repeat('a', 401));
$emptyNameProject = array('projectName' => '', 'projectKey' => 'valid_key');
$emptyKeyProject = array('projectName' => 'valid_name', 'projectKey' => '');

$sonarqube = new sonarqubeModelTest();

r($sonarqube->createProjectTest($sonarqubeID, $validProject))       && p() && e(1);                      // 步骤1：正常数据创建项目
r($sonarqube->createProjectTest(0, $validProject))                 && p() && e('return false');         // 步骤2：无效sonarqubeID创建项目
r($sonarqube->createProjectTest($sonarqubeID, $invalidKeyProject)) && p() && e('return error');         // 步骤3：项目键格式错误创建项目
r($sonarqube->createProjectTest($sonarqubeID, $longNameProject))   && p() && e('return false');         // 步骤4：项目名称长度超限创建项目
r($sonarqube->createProjectTest($sonarqubeID, $longKeyProject))    && p('projectKey:0') && e('『项目标识』长度应当不超过『400』'); // 步骤5：项目键长度超限创建项目
r($sonarqube->createProjectTest($sonarqubeID, $emptyNameProject))  && p('name:0') && e("The 'name' parameter is missing");     // 步骤6：空项目名称创建项目
r($sonarqube->createProjectTest($sonarqubeID, $emptyKeyProject))   && p('name:0') && e("The 'project' parameter is missing");  // 步骤7：空项目键创建项目