#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sonarqube.class.php';
su('admin');

/**

title=测试 sonarqubeModel::apiCreateProject();
cid=1
pid=1

使用空的sonarqubeID、项目对象创建sonarqube项目 >> return false
使用空的sonarqubeID、正确的项目对象创建sonarqube项目 >> return false
使用正确的sonarqubeID,项目对象创建sonarqube项目 >> unit test
使用重复的项目对象创建sonarqube项目 >> Could not create Project, key already exists: unit_test

*/

$sonarqubeID = 2;

$t_empty_project = array();
$t_project       = array('projectName' => 'unit_test', 'projectKey' => 'unit_test');

$sonarqube = new sonarqubeTest();
r($sonarqube->apiCreateProjectTest(0, $t_empty_project))      && p()              && e('return false');                                            //使用空的sonarqubeID、项目对象创建sonarqube项目
r($sonarqube->apiCreateProjectTest(0, $t_project))            && p()              && e('return false');                                            //使用空的sonarqubeID、正确的项目对象创建sonarqube项目
$sonarqube->apiDeleteProjectTest($sonarqubeID, 'unit_test');
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_project)) && p('project:key') && e('unit test');                                               //使用正确的sonarqubeID,项目对象创建sonarqube项目
r($sonarqube->apiCreateProjectTest($sonarqubeID, $t_project)) && p('0:msg')       && e("Could not create Project, key already exists: unit_test"); //使用重复的项目对象创建sonarqube项目