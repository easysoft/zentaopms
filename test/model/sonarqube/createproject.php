#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::createProject();
cid=1
pid=1

使用空的项目数据，空的sonarqubeID创建Sonarqube项目 >> 『项目名称』不能为空
使用错误项目数据，正确的sonarqubeID创建Sonarqube项目 >> return error
使用正确的项目数据，空的sonarqubeID创建Sonarqube项目 >> return false

*/

$sonarqubeID = 2;

$empty_post = array('projectName' => '', 'projectKey' => '');
$error_post = array('projectName' => 'unit_test', 'projectKey' => '@#');
$true_post  = array('projectName' => 'unit_test', 'projectKey' => 'unit_test');

$sonarqube = new sonarqubeTest();
r($sonarqube->createProjectTest(0, $empty_post))            && p('projectName:0') && e('『项目名称』不能为空'); //使用空的项目数据，空的sonarqubeID创建Sonarqube项目
r($sonarqube->createProjectTest($sonarqubeID, $error_post)) && p()                && e("return error");         //使用错误项目数据，正确的sonarqubeID创建Sonarqube项目
r($sonarqube->createProjectTest(0, $true_post))             && p()                && e('return false');         //使用正确的项目数据，空的sonarqubeID创建Sonarqube项目
$sonarqube->apiDeleteProjectTest($sonarqubeID, 'unit_test');
r($sonarqube->createProjectTest($sonarqubeID, $true_post))  && p()                && e(1);                      //使用正确的sonarqubeID和Sonarqube项目信息创建Sonarqube项目