#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::createProject();
cid=1
pid=1

使用空的项目数据，空的sonarqubeID创建Sonarqube项目        >> 『项目名称』不能为空
使用错误项目数据，正确的sonarqubeID创建Sonarqube项目      >> return error
使用正确的项目数据，空的sonarqubeID创建Sonarqube项目      >> return false
使用正确的sonarqubeID和Sonarqube项目信息创建Sonarqube项目 >> 1

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 0;
$_POST['projectName'] = '';
$_POST['projectKey']  = '';
$sonarqube->createProject($sonarqubeID);
r(dao::getError()) && p('projectName:0') && e('『项目名称』不能为空'); //使用空的项目数据，空的sonarqubeID创建Sonarqube项目

$sonarqubeID = 2;
$_POST['projectName'] = 'unit_test';
$_POST['projectKey']  = '@#';
$sonarqube->createProject($sonarqubeID);
$errors = dao::getError();
if(array_shift($errors) == "项目标识的格式不正确。允许的字符为字母、数字、'-'、''、'.'和“：”，至少有一个非数字。") $result = 'return error';
r($result) && p() && e("return error"); //使用错误项目数据，正确的sonarqubeID创建Sonarqube项目

$sonarqubeID = 0;
$_POST['projectName'] = 'unit_test';
$_POST['projectKey']  = 'unit_test';
$result = $sonarqube->createProject($sonarqubeID);
if(empty($result)) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的项目数据，空的sonarqubeID创建Sonarqube项目

dao::getError();
$sonarqubeID = 2;
$sonarqube->apiDeleteProject($sonarqubeID, $_POST['projectKey']);
$result = $sonarqube->createProject($sonarqubeID);
r($result) && p() && e(1); //使用正确的sonarqubeID和Sonarqube项目信息创建Sonarqube项目
