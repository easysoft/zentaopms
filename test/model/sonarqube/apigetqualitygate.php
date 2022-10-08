#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiGetQualitygate();
cid=1
pid=1

通过错误的sonarqubeID、projectKey，查询sonarqube质量门信息 >> return empty
通过正确的sonarqubeID,不存在的projectKey查询sonarqube质量门信息 >> Project 'wrong_project' not found
通过正确的sonarqubeID、projectKey查询sonarqube质量门信息 >> success

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 0;
$projectKey  = '';
$result      = $sonarqube->apiGetQualitygate($sonarqubeID, $projectKey);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //通过错误的sonarqubeID、projectKey，查询sonarqube质量门信息

$sonarqubeID = 2;
$projectKey  = 'wrong_project';
$result      = $sonarqube->apiGetQualitygate($sonarqubeID, $projectKey);
r($result->errors) && p('0:msg') && e("Project 'wrong_project' not found"); //通过正确的sonarqubeID,不存在的projectKey查询sonarqube质量门信息

$projectKey = 'zentaopms';
$result     = $sonarqube->apiGetReport($sonarqubeID, $projectKey);
if(!empty($result->component)) $result = 'success';
r($result) && p() && e("success"); //通过正确的sonarqubeID、projectKey查询sonarqube质量门信息