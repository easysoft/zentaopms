#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiCreateProject();
timeout=0
cid=18373

- 测试步骤1：使用无效的sonarqubeID创建项目 @return false
- 测试步骤2：使用空项目对象创建项目 @object result
- 测试步骤3：使用正确的sonarqubeID和项目对象创建项目 @object result
- 测试步骤4：使用包含特殊字符的项目名称创建项目 @object result
- 测试步骤5：使用包含特殊字符的项目key创建项目 @object result
- 测试步骤6：测试项目名称和key为空的情况 @object result
- 测试步骤7：测试极长项目名称的边界情况 @object result

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';
su('admin');

zenData('pipeline')->loadYaml('pipeline')->gen(5);

$sonarqubeID = 2;

$t_empty_project = array();
$t_project = array('projectName' => 'unit_test17', 'projectKey' => 'unittest17');
$t_special_name_project = array('projectName' => 'Test Project (测试)', 'projectKey' => 'test-project-special');
$t_special_key_project = array('projectName' => 'Special Key Test', 'projectKey' => 'special.key:test-123');
$t_empty_values_project = array('projectName' => '', 'projectKey' => '');
$t_long_name_project = array('projectName' => str_repeat('Test Project Name ', 20), 'projectKey' => 'long-name-test');

$sonarqube = new sonarqubeTest();
r($sonarqube->apiCreateProjectTest(0, $t_empty_project)) && p() && e('return false'); // 测试步骤1：使用无效的sonarqubeID创建项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_empty_project)) && p() && e('object result'); // 测试步骤2：使用空项目对象创建项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_project)) && p() && e('object result'); // 测试步骤3：使用正确的sonarqubeID和项目对象创建项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_special_name_project)) && p() && e('object result'); // 测试步骤4：使用包含特殊字符的项目名称创建项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_special_key_project)) && p() && e('object result'); // 测试步骤5：使用包含特殊字符的项目key创建项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_empty_values_project)) && p() && e('object result'); // 测试步骤6：测试项目名称和key为空的情况
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_long_name_project)) && p() && e('object result'); // 测试步骤7：测试极长项目名称的边界情况