#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiDeleteProject();
cid=1
pid=1

通过不正确的sonarqubeID、projectKey,删除SonarQube项目  >> return false
正确的sonarqubeID,空的projectKey,删除SonarQube项目     >> The 'project' parameter is missing
正确的sonarqubeID,不存在的projectKey,删除SonarQube项目 >> Project 'no_project' not found
正确的sonarqubeID、projectKey,删除SonarQube项目        >> return true

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 0;
$projectKey  = '';
$result      = $sonarqube->apiDeleteProject($sonarqubeID, $projectKey);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');    //通过不正确的sonarqubeID、projectKey,删除SonarQube项目

$sonarqubeID = 2;
$projectKey  = '';
$result      = $sonarqube->apiDeleteProject($sonarqubeID, $projectKey);
r($result->errors) && p('0:msg') && e("The 'project' parameter is missing"); //正确的sonarqubeID,空的projectKey,删除SonarQube项目

$projectKey = 'no_project';
$result     = $sonarqube->apiDeleteProject($sonarqubeID, $projectKey);
r($result->errors) && p('0:msg') && e("Project 'no_project' not found"); //正确的sonarqubeID,不存在的projectKey,删除SonarQube项目

$projectKey = 'new_project';
list($apiRoot, $header) = $sonarqube->getApiBase($sonarqubeID);
$url    = sprintf($apiRoot, "projects/create?name=$projectKey&project=$projectKey");
commonModel::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header);

$result = $sonarqube->apiDeleteProject($sonarqubeID, $projectKey);
if($result === null) $result = 'return true';
r($result) && p() && e("return true"); //正确的sonarqubeID、projectKey,删除SonarQube项目
