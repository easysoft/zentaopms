#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiCreateProject();
cid=1
pid=1

使用空的sonarqubeID、项目对象创建sonarqube项目       >> return false
使用空的sonarqubeID、正确的项目对象创建sonarqube项目 >> return false
使用正确的sonarqubeID,项目对象创建sonarqube项目      >> unit_test
使用重复的项目对象创建sonarqube项目                  >> Could not create Project, key already exists: unit_test

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 0;
$project     = new stdclass();

$result = $sonarqube->apiCreateProject($sonarqubeID, $project);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的sonarqubeID、项目对象创建sonarqube项目

$project->projectName = 'unit_test';
$project->projectKey  = 'unit_test';
$result = $sonarqube->apiCreateProject($sonarqubeID, $project);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的sonarqubeID、正确的项目对象创建sonarqube项目

$sonarqubeID = 2;
$sonarqube->apiDeleteProject($sonarqubeID, $project->projectKey);
$result = $sonarqube->apiCreateProject($sonarqubeID, $project);
r($result) && p('project:key') && e('unit test'); //使用正确的sonarqubeID,项目对象创建sonarqube项目
$result = $sonarqube->apiCreateProject($sonarqubeID, $project);
r($result->errors) && p('0:msg') && e("Could not create Project, key already exists: unit_test"); //使用重复的项目对象创建sonarqube项目
