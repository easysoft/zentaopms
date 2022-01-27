#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 sonarqubeModel::apiGetProjects();
cid=1
pid=1

通过sonarqubeID,获取SonarQube项目列表        >> 1
通过sonarqubeID,获取SonarQube项目数量        >> 1
通过sonarqubeID,关键字,搜索获取SonarQube项目 >> 1
当sonarqubeID为0时,获取SonarQube项目列表     >> return empty

*/

$sonarqube = $tester->loadModel('sonarqube');

$sonarqubeID = 2;
$result      = $sonarqube->apiGetProjects($sonarqubeID);
r(isset($result[0]->name)) && p() && e('1'); //通过sonarqubeID,获取SonarQube项目列表
r(count($result) > 0)      && p() && e('1'); //通过sonarqubeID,获取SonarQube项目数量

$keyword = '02';
$result  = $sonarqube->apiGetProjects($sonarqubeID, $keyword);
r(strpos($result[0]->name, '02') !== false) && p() && e('1'); //通过sonarqubeID,关键字,搜索获取SonarQube项目

$sonarqubeID = 0;
$result      = $sonarqube->apiGetProjects($sonarqubeID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当sonarqubeID为0时,获取SonarQube项目列表
