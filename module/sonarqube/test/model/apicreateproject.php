#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeModel::apiCreateProject();
cid=0

- 使用空的sonarqubeID、项目对象创建sonarqube项目 @return false
- 使用空的sonarqubeID、正确的项目对象创建sonarqube项目 @return false
- 使用正确的sonarqubeID,项目对象创建sonarqube项目 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/sonarqube.class.php';
su('admin');

zdTable('pipeline')->config('pipeline')->gen(5);

$sonarqubeID = 2;

$t_empty_project = array();
$t_project       = array('projectName' => 'unit_test17', 'projectKey' => 'unittest17');

$sonarqube = new sonarqubeTest();
r($sonarqube->apiCreateProjectTest(0, $t_empty_project))      && p() && e('return false');                                            //使用空的sonarqubeID、项目对象创建sonarqube项目
r($sonarqube->apiCreateProjectTest(0, $t_project))            && p() && e('return false');                                            //使用空的sonarqubeID、正确的项目对象创建sonarqube项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_project)) && p() && e('1');                                               //使用正确的sonarqubeID,项目对象创建sonarqube项目
