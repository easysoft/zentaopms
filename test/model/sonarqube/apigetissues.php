#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::apiGetIssues();
cid=1
pid=1

通过sonarqubeID,获取SonarQube问题列表 >> 1
通过sonarqubeID,项目key获取SonarQube问题列表 >> 1
当sonarqubeID为0时,获取SonarQube问题列表 >> return empty

*/

$sonarqubeID = 2;
$projectKey  = 'zentaopms';

$sonarqube = new sonarqubeTest();
r($sonarqube->apiGetIssuesTest($sonarqubeID))              && p() && e('1');            //通过sonarqubeID,获取SonarQube问题列表
r($sonarqube->apiGetIssuesTest($sonarqubeID, $projectKey)) && p() && e('1');            //通过sonarqubeID,项目key获取SonarQube问题列表
r($sonarqube->apiGetIssuesTest(0))                         && p() && e('return empty'); //当sonarqubeID为0时,获取SonarQube问题列表