#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiGetProjects();
cid=0

- 通过sonarqubeID,获取SonarQube项目列表 @1
- 通过sonarqubeID,获取SonarQube项目数量 @1
- 通过sonarqubeID,关键字,搜索获取SonarQube项目第0条的name属性 @unittest
- 当sonarqubeID为0时,获取SonarQube项目列表 @return empty

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;
$keyword     = 'unit';

$sonarqube = new sonarqubeTest();
$result    = $sonarqube->apiGetProjectsTest($sonarqubeID);
r(isset($result[0]->name))                                 && p()         && e('1'); //通过sonarqubeID,获取SonarQube项目列表
r(count($result) > 1)                                      && p()         && e('1'); //通过sonarqubeID,获取SonarQube项目数量
r($sonarqube->apiGetProjectsTest($sonarqubeID, $keyword))  && p('0:name') && e('unittest');            //通过sonarqubeID,关键字,搜索获取SonarQube项目
r($sonarqube->apiGetProjectsTest(0))                       && p()         && e('return empty'); //当sonarqubeID为0时,获取SonarQube项目列表
