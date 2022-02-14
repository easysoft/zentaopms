#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiGetIssues();
cid=1
pid=1

通过sonarqubeID,获取SonarQube问题列表        >> 1
通过sonarqubeID,项目key获取SonarQube问题列表 >> 1
当sonarqubeID为0时,获取SonarQube问题列表     >> return empty

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 2;
$result      = $sonarqube->apiGetIssues($sonarqubeID);
r(isset($result[0]->message)) && p() && e('1'); //通过sonarqubeID,获取SonarQube问题列表

$projectKey = 'zentaopms';
$result     = $sonarqube->apiGetIssues($sonarqubeID, $projectKey);
r(isset($result[0]->message)) && p() && e('1'); //通过sonarqubeID,项目key获取SonarQube问题列表

$sonarqubeID = 0;
$result      = $sonarqube->apiGetIssues($sonarqubeID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当sonarqubeID为0时,获取SonarQube问题列表
