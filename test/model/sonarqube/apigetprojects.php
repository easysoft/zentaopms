#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::apiGetProjects();
cid=1
pid=1

通过sonarqubeID,获取SonarQube项目列表 >> 1
通过sonarqubeID,获取SonarQube项目数量 >> 1
通过sonarqubeID,关键字,搜索获取SonarQube项目 >> 1
当sonarqubeID为0时,获取SonarQube项目列表 >> return empty

*/

$sonarqubeID = 2;
$keyword     = '02';

$sonarqube = new sonarqubeTest();
r($sonarqube->apiGetProjectsTest($sonarqubeID))            && p() && e('1');            //通过sonarqubeID,获取SonarQube项目列表
r(count($sonarqube->apiGetProjectsTest($sonarqubeID)) > 0) && p() && e('1');            //通过sonarqubeID,获取SonarQube项目数量
r($sonarqube->apiGetProjectsTest($sonarqubeID, $keyword))  && p() && e('1');            //通过sonarqubeID,关键字,搜索获取SonarQube项目
r($sonarqube->apiGetProjectsTest(0))                       && p() && e('return empty'); //当sonarqubeID为0时,获取SonarQube项目列表