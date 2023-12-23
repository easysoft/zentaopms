#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::createProject();
timeout=0
cid=1

- 使用错误项目数据，正确的sonarqubeID创建Sonarqube项目 @return error
- 使用正确的项目数据，空的sonarqubeID创建Sonarqube项目 @return false
- 使用正确的sonarqubeID和Sonarqube项目信息创建Sonarqube项目 @1

*/

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;

$empty_post = array('projectName' => '', 'projectKey' => '');
$error_post = array('projectName' => 'unit_test', 'projectKey' => '@#');
$true_post  = array('projectName' => 'unit_test17', 'projectKey' => 'unittest17');

$sonarqube = new sonarqubeTest();
r($sonarqube->createProjectTest($sonarqubeID, $error_post)) && p()                && e("return error");         //使用错误项目数据，正确的sonarqubeID创建Sonarqube项目
r($sonarqube->createProjectTest(0, $true_post))             && p()                && e('return false');         //使用正确的项目数据，空的sonarqubeID创建Sonarqube项目
r($sonarqube->createProjectTest($sonarqubeID, $true_post))  && p()                && e(1);                      //使用正确的sonarqubeID和Sonarqube项目信息创建Sonarqube项目