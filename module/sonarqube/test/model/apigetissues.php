#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetIssues();
cid=0

- 通过sonarqubeID,项目key获取SonarQube问题列表 @1
- 通过sonarqubeID,项目key获取SonarQube问题列表数量 @1
- 当sonarqubeID为0时,获取SonarQube问题列表 @return empty

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';
su('admin');

zenData('pipeline')->loadYaml('pipeline')->gen(5);

$sonarqubeID = 2;
$projectKey  = 'bendi';

$sonarqube = new sonarqubeTest();
$result = $sonarqube->apiGetIssuesTest($sonarqubeID, $projectKey);
r(isset($result[0]->message))      && p() && e('1');            //通过sonarqubeID,项目key获取SonarQube问题列表
r(count($result) > 100)            && p() && e('1');            //通过sonarqubeID,项目key获取SonarQube问题列表数量
r($sonarqube->apiGetIssuesTest(0)) && p() && e('return empty'); //当sonarqubeID为0时,获取SonarQube问题列表
